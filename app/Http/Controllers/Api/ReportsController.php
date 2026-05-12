<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Http\Transformers\ActionlogsTransformer;
use App\Models\Accessory;
use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\License;
use App\Models\LicenseSeat;
use App\Models\Maintenance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportsController extends Controller
{
    /**
     * Returns Activity Report JSON.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     *
     * @since [v4.0]
     */
    public function index(FilterRequest $request): JsonResponse|array
    {

        // If the user doesn't have permission to view the item or the target,
        // then they shouldn't be able to see the activity log for that item or target,
        // but if they have the general activity view permission,
        // then they can see all activity logs regardless of the item or target.
        if ((! Gate::allows('activity.view')) && (($request->filled('target_type')) && ($request->filled('target_id'))) || (($request->filled('item_type')) && ($request->filled('item_id')))) {

            if (($request->filled('target_type')) && ($request->filled('target_id'))) {
                $target = Helper::normalizeFullModelName(request()->input('target_type'));
                $target::find(request()->input('target_id'))?->withTrashed();
                $this->authorize('view', $target);
            }

            if (($request->filled('item_type')) && ($request->filled('item_id'))) {
                $item = Helper::normalizeFullModelName(request()->input('item_type'));
                $item::find(request()->input('item_id'))?->withTrashed();
                $this->authorize('view', $item);
            }

        } else {
            $this->authorize('activity.view');
        }

        $actionlogs = Actionlog::with('item', 'user', 'adminuser', 'target', 'location');

        if (($request->filled('target_type')) && ($request->filled('target_id'))) {
            $actionlogs = $actionlogs->where('target_id', '=', $request->input('target_id'))
                ->where('target_type', '=', Helper::normalizeFullModelName($request->input('target_type')));
        }

        if (($request->filled('item_type')) && ($request->filled('item_id'))) {
            $actionlogs = $actionlogs->where(function ($query) use ($request) {
                $query->where('item_id', '=', $request->input('item_id'))
                    ->where('item_type', '=', Helper::normalizeFullModelName($request->input('item_type')))
                    ->orWhere(function ($query) use ($request) {
                        $query->where('target_id', '=', $request->input('item_id'))
                            ->where('target_type', '=', Helper::normalizeFullModelName($request->input('item_type')));
                    });
            });
        }

        // This invokes the Searchable model trait scopeTextSearch and will handle input by search or by advanced search filter
        if ($request->filled('filter') || $request->filled('search')) {
            $actionlogs->TextSearch($request->input('filter') ? $request->input('filter') : $request->input('search'));
        }

        if ($request->filled('action_type')) {
            $actionlogs = $actionlogs->where('action_type', '=', $request->input('action_type'));
        }

        if ($request->filled('created_by')) {
            $actionlogs = $actionlogs->where('created_by', '=', $request->input('created_by'));
        }

        if ($request->filled('action_source')) {
            $actionlogs = $actionlogs->where('action_source', '=', $request->input('action_source'));
        }

        if ($request->filled('remote_ip')) {
            $actionlogs = $actionlogs->where('remote_ip', '=', $request->input('remote_ip'));
        }

        if ($request->filled('uploads')) {
            $actionlogs = $actionlogs->whereNotNull('filename');
        }

        $allowed_columns = [
            'id',
            'created_at',
            'target_id',
            'created_by',
            'accept_signature',
            'action_type',
            'note',
            'remote_ip',
            'user_agent',
            'target_type',
            'item_type',
            'action_source',
            'action_date',
        ];

        $total = $actionlogs->count();
        // Make sure the offset and limit are actually integers and do not exceed system limits
        $offset = ($request->input('offset') > $total) ? $total : app('api_offset_value');
        $limit = app('api_limit_value');

        $order = ($request->input('order') == 'asc') ? 'asc' : 'desc';

        switch ($request->input('sort')) {
            case 'created_by':
                $actionlogs->OrderByCreatedBy($order);
                break;
            default:
                $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'action_logs.created_at';
                $actionlogs = $actionlogs->orderBy($sort, $order);
                break;
        }

        $actionlogs = $actionlogs->skip($offset)->take($limit)->get();

        return response()->json((new ActionlogsTransformer)->transformActionlogs($actionlogs, $total), 200, ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);

    }

    /**
     * Returns time-series data for the reports overview charts.
     *
     * Accepts ?days=N (preset, default 30) OR ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD.
     * Also returns the immediately preceding period of equal length for comparison lines.
     */
    public function activityChart(Request $request): JsonResponse
    {
        $this->authorize('reports.view');

        $allowedDays = [7, 14, 30, 60, 90, 180, 365];

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $curStart = Carbon::parse($request->input('start_date'))->startOfDay();
            $curEnd = Carbon::parse($request->input('end_date'))->endOfDay();
            if ($curEnd->lt($curStart)) {
                [$curStart, $curEnd] = [$curEnd, $curStart];
            }
            $days = max(1, (int) $curStart->diffInDays($curEnd) + 1);
        } else {
            $days = in_array((int) $request->input('days'), $allowedDays) ? (int) $request->input('days') : 30;
            $curEnd = Carbon::today()->endOfDay();
            $curStart = Carbon::today()->subDays($days - 1)->startOfDay();
        }

        $prevEnd = $curStart->copy()->subSecond()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($days - 1)->startOfDay();

        $buildDates = function (Carbon $start, Carbon $end): array {
            $dates = [];
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $dates[] = $d->toDateString();
            }

            return $dates;
        };

        $curDates = $buildDates($curStart, $curEnd);
        $prevDates = $buildDates($prevStart, $prevEnd);

        $pluckAction = function (string $actionType, Carbon $start, Carbon $end): array {
            return Actionlog::where('action_type', $actionType)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        };

        // withTrashed() ensures records deleted after creation still appear in their creation-period counts.
        $pluckCreated = function (string $modelClass, Carbon $start, Carbon $end): array {
            return $modelClass::withTrashed()
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        };

        // Maintenance has no company_id column and no CompanyableTrait, so scope through
        // its asset relationship — whereHas('asset') applies Asset's FMCS global scope.
        $pluckMaintenances = function (Carbon $start, Carbon $end): array {
            return Maintenance::withTrashed()
                ->whereHas('asset')
                ->whereBetween('maintenances.created_at', [$start, $end])
                ->selectRaw('DATE(maintenances.created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        };

        // Filters by both action_type and item_type for per-category checkout/checkin counts.
        $pluckActionByType = function (string $actionType, string $modelClass, Carbon $start, Carbon $end): array {
            return Actionlog::where('action_type', $actionType)
                ->where('item_type', $modelClass)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        };

        $pluckDeletedUsers = function (Carbon $start, Carbon $end): array {
            return User::withTrashed()
                ->whereNotNull('deleted_at')
                ->whereBetween('deleted_at', [$start, $end])
                ->selectRaw('DATE(deleted_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        };

        // Catches both 'checkin' and 'checkin from' action types used across different item types.
        $pluckCheckinsByType = function (string $modelClass, Carbon $start, Carbon $end): array {
            return Actionlog::whereIn('action_type', ['checkin', 'checkin from'])
                ->where('item_type', $modelClass)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        };

        $fill = fn (array $raw, array $dates) => array_map(fn ($d) => (int) ($raw[$d] ?? 0), $dates);

        $datasets = [];
        foreach ([
            'new_users' => fn ($s, $e) => $pluckCreated(User::class, $s, $e),
            'deleted_users' => fn ($s, $e) => $pluckDeletedUsers($s, $e),
            'asset_checkouts' => fn ($s, $e) => $pluckActionByType('checkout', Asset::class, $s, $e),
            'asset_checkins' => fn ($s, $e) => $pluckCheckinsByType(Asset::class, $s, $e),
            'new_assets' => fn ($s, $e) => $pluckCreated(Asset::class, $s, $e),
            'new_maintenances' => fn ($s, $e) => $pluckMaintenances($s, $e),
            'new_audits' => fn ($s, $e) => $pluckAction('audit', $s, $e),
            'component_checkouts' => fn ($s, $e) => $pluckActionByType('checkout', Component::class, $s, $e),
            'component_checkins' => fn ($s, $e) => $pluckCheckinsByType(Component::class, $s, $e),
            'new_components' => fn ($s, $e) => $pluckCreated(Component::class, $s, $e),
            'consumable_checkouts' => fn ($s, $e) => $pluckActionByType('checkout', Consumable::class, $s, $e),
            'consumable_checkins' => fn ($s, $e) => $pluckCheckinsByType(Consumable::class, $s, $e),
            'new_consumables' => fn ($s, $e) => $pluckCreated(Consumable::class, $s, $e),
            'license_checkouts' => fn ($s, $e) => $pluckActionByType('checkout', LicenseSeat::class, $s, $e),
            'license_checkins' => fn ($s, $e) => $pluckCheckinsByType(LicenseSeat::class, $s, $e),
            'new_licenses' => fn ($s, $e) => $pluckCreated(License::class, $s, $e),
            'accessory_checkouts' => fn ($s, $e) => $pluckActionByType('checkout', Accessory::class, $s, $e),
            'accessory_checkins' => fn ($s, $e) => $pluckCheckinsByType(Accessory::class, $s, $e),
            'new_accessories' => fn ($s, $e) => $pluckCreated(Accessory::class, $s, $e),
        ] as $key => $query) {
            $datasets[$key] = $fill($query($curStart, $curEnd), $curDates);
            $datasets['prev_'.$key] = $fill($query($prevStart, $prevEnd), $prevDates);
        }

        return response()->json(array_merge([
            'labels' => array_map(fn ($d) => Carbon::parse($d)->format('M j'), $curDates),
            'prev_label' => $prevStart->format('M j').' – '.$prevEnd->format('M j'),
        ], $datasets));
    }
}
