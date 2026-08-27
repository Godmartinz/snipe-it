<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\CalendarEventsTransformer;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Unified read endpoint for the calendar page. Queries the
 * calendar_events index table (populated by the HasCalendarEvents
 * trait's observer) and hydrates each row from its live source
 * model, so titles / urls / colors reflect current source state
 * rather than a denormalized snapshot.
 *
 * Filtering:
 *   - `start`, `end` (ISO-8601) - FullCalendar's visible range.
 *     Defaults to a wide six-month window centered on today.
 *   - `event_type[]` - array of allowed event_type values. Empty
 *     means "all types". Frontend filter buttons flip these on and
 *     off.
 *   - `limit` - hard cap on returned events. Defaults to 500 for the
 *     full calendar page and gets overridden downward for the
 *     dashboard "Today" widget. Guards the browser from a 10k-row
 *     JSON blob when a big install's calendar range straddles a
 *     dense date. Response is `{ events, truncated, total }` so the
 *     frontend can render a "narrow filters" banner when truncated.
 *
 * Access control:
 *   - Base gate: the viewer must be able to view AT LEAST ONE of the
 *     source types (Maintenance / Asset / License / User). A user
 *     with only license-view permission still legitimately wants the
 *     calendar to render their license expirations, so the endpoint
 *     shouldn't 403 them just because they can't see assets.
 *   - Source-specific access is enforced per-row via each source's
 *     own view policy - if the actor can't view the underlying model
 *     (FMCS mismatch, location scoping, etc.), the event is filtered
 *     out before it hits the response. That per-row gate does the
 *     actual scoping work.
 */
class CalendarEventsController extends Controller
{
    /**
     * Hard cap on returned rows. Two calendar contexts hit this
     * endpoint: the full calendar page (default 500) and the
     * dashboard's Today widget (much smaller, sends ?limit=25). A
     * request that asks for more than the ceiling silently gets
     * capped so the browser can't be exhausted.
     */
    private const LIMIT_CEILING = 500;

    public function index(Request $request): JsonResponse
    {
        $this->authorizeAnySource($request);

        [$rangeStart, $rangeEnd] = $this->resolveRange($request);
        $eventTypes = $this->resolveEventTypes($request);
        $limit = $this->resolveLimit($request);

        $baseQuery = $this->buildBaseQuery($rangeStart, $rangeEnd, $eventTypes);
        $total = (clone $baseQuery)->count();
        $rows = $baseQuery->orderBy('start')->limit($limit)->get();

        // Whether we hit the query limit. Honest signal for "there
        // might be more events after this batch". Comparing $total to
        // count($events) post-per-row-filter would report truncated=
        // true any time an FMCS-filtered event brought the returned
        // count below $total, misleading users into clicking "+N more"
        // links for events they never had permission to see.
        $hitLimit = $rows->count() >= $limit;

        $sourcesByType = $this->batchLoadSources($rows);
        $authorizedRows = $this->filterAuthorizedRows($rows, $sourcesByType, $request);

        return response()->json([
            'events' => (new CalendarEventsTransformer)->transformCalendarEvents($authorizedRows, $sourcesByType),
            'total' => $total,
            'truncated' => $hitLimit,
        ]);
    }

    /**
     * Base gate: viewer must be able to view AT LEAST ONE registered
     * HasCalendarEvents source model. Source list comes from
     * CalendarEvent::sourceModels() so a new adopter of the trait is
     * picked up automatically. Per-row policy checks inside
     * buildEvents() handle the actual event-level scoping.
     */
    protected function authorizeAnySource(Request $request): void
    {
        $viewer = $request->user();
        foreach (CalendarEvent::sourceModels() as $sourceClass) {
            if ($viewer?->can('view', $sourceClass)) {
                return;
            }
        }
        abort(403);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function resolveRange(Request $request): array
    {
        return [
            $request->filled('start')
                ? Carbon::parse($request->input('start'))
                : now()->subMonths(3)->startOfDay(),
            $request->filled('end')
                ? Carbon::parse($request->input('end'))
                : now()->addMonths(3)->endOfDay(),
        ];
    }

    /**
     * `event_type[]=a&event_type[]=b` (repeatable) or `event_type=a,b`
     * (single param, comma-split). Empty or missing means "all types".
     *
     * @return array<int, string>
     */
    protected function resolveEventTypes(Request $request): array
    {
        $eventTypes = $request->input('event_type');
        if (is_string($eventTypes)) {
            $eventTypes = array_filter(array_map('trim', explode(',', $eventTypes)));
        }

        return is_array($eventTypes) ? array_values(array_filter($eventTypes)) : [];
    }

    protected function resolveLimit(Request $request): int
    {
        $limit = (int) $request->input('limit', self::LIMIT_CEILING);

        return max(1, min($limit, self::LIMIT_CEILING));
    }

    /**
     * FullCalendar's fetchInfo passes ranges as [start, end) (end
     * exclusive), so a listDay view of today is start=today 00:00,
     * end=tomorrow 00:00. whereBetween is inclusive on both sides
     * though, which pulled tomorrow's midnight events into today's
     * listDay widget and inflated the truncation count. Explicit
     * `>= start` / `< end` matches FC's half-open interval semantics.
     */
    protected function buildBaseQuery(Carbon $rangeStart, Carbon $rangeEnd, array $eventTypes)
    {
        return CalendarEvent::query()
            ->where(function ($q) use ($rangeStart, $rangeEnd) {
                $q->where(function ($inner) use ($rangeStart, $rangeEnd) {
                    $inner->where('start', '>=', $rangeStart)
                        ->where('start', '<', $rangeEnd);
                })
                    ->orWhere(function ($inner) use ($rangeStart, $rangeEnd) {
                        $inner->where('end', '>=', $rangeStart)
                            ->where('end', '<', $rangeEnd);
                    })
                    ->orWhere(function ($inner) use ($rangeStart, $rangeEnd) {
                        $inner->where('start', '<', $rangeStart)
                            ->where('end', '>=', $rangeEnd);
                    });
            })
            ->when(! empty($eventTypes), fn ($q) => $q->whereIn('event_type', $eventTypes));
    }

    /**
     * One query per distinct source_type, keyed by id for O(1) lookup
     * in buildEvents. Avoids the N+1 a naive $row->source access
     * would produce. Includes trashed sources when the model uses
     * SoftDeletes so an event whose parent was soft-deleted between
     * the index write and the request still resolves for display.
     *
     * @return array<class-string, \Illuminate\Support\Collection>
     */
    protected function batchLoadSources($rows): array
    {
        $sourcesByType = [];
        foreach ($rows->groupBy('source_type') as $sourceType => $typeRows) {
            if (! class_exists($sourceType)) {
                continue;
            }
            $ids = $typeRows->pluck('source_id')->unique()->all();
            $sourcesByType[$sourceType] = $sourceType::query()
                ->when(
                    in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive($sourceType), true),
                    fn ($q) => $q->withTrashed(),
                )
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id');
        }

        return $sourcesByType;
    }

    /**
     * Filters rows down to those whose source still exists AND whose
     * source the current viewer can `view` under its own policy. Actual
     * event shaping happens in CalendarEventsTransformer once the row
     * set is known-safe to serialize.
     *
     * Per-row access via each source's own view policy: without this,
     * view-Asset holders without a particular license access would
     * still see its expiration event. Missing source means the parent
     * was deleted between the index write and now (mid-request delete
     * without a completed observer cascade); reconcile command cleans
     * up the orphan on its next pass.
     *
     * @return \Illuminate\Support\Collection<int, CalendarEvent>
     */
    protected function filterAuthorizedRows($rows, array $sourcesByType, Request $request)
    {
        $viewer = $request->user();

        return $rows->filter(function (CalendarEvent $row) use ($sourcesByType, $viewer) {
            $source = $sourcesByType[$row->source_type][$row->source_id] ?? null;

            return $source && $viewer?->can('view', $source);
        })->values();
    }
}
