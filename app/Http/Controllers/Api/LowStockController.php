<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\LowStockTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Unified low-stock endpoint for the dashboard widget. Delegates the
 * actual "which rows count as low" logic to Helper::checkLowInventory()
 * so this API and the top-nav alert bell (livewire:alert-menu) share a
 * single source of truth. The helper factors in current checkouts and
 * the site-wide alert_threshold buffer, and it covers five source
 * models (Consumable / Accessory / Component / AssetModel / License) —
 * routing through it here avoids ever duplicating that logic on the
 * widget side and drifting from what the alert bell shows.
 *
 * Search / sort / paginate happen after the helper returns, on a
 * bounded set (only rows the helper flagged as alertable, which is
 * small by definition), so PHP-side ordering is cheap here.
 */
class LowStockController extends Controller
{
    /**
     * Sort keys that bs-table can toggle from a column header. Anything
     * else falls back to remaining_asc (most urgent first).
     */
    private const SORTABLE_COLUMNS = ['name', 'type', 'qty', 'min_amt', 'remaining', 'percent'];

    public function index(Request $request): JsonResponse|array
    {
        // Base access gate. Anyone who can view any of the source types
        // can see the widget; per-row permissions (adjust_quantity)
        // still gate the action buttons inside the transformer.
        $viewer = auth()->user();
        $canView = false;
        foreach (LowStockTransformer::sourceModels() as $modelClass) {
            if ($viewer?->can('view', $modelClass)) {
                $canView = true;
                break;
            }
        }
        if (! $canView) {
            abort(403);
        }

        $rows = Helper::checkLowInventory();

        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $rows = array_values(array_filter($rows, fn ($r) => str_contains(strtolower($r['name']), strtolower($search))));
        }

        $sort = in_array($request->input('sort'), self::SORTABLE_COLUMNS, true) ? $request->input('sort') : 'remaining';
        $order = $request->input('order') === 'desc' ? 'desc' : 'asc';

        usort($rows, function ($a, $b) use ($sort) {
            return match ($sort) {
                'name' => strcasecmp((string) $a['name'], (string) $b['name']),
                'type' => strcmp((string) $a['type'], (string) $b['type']),
                'qty' => (int) ($a['qty'] ?? 0) <=> (int) ($b['qty'] ?? 0),
                'min_amt' => (int) $a['min_amt'] <=> (int) $b['min_amt'],
                'percent' => (int) $a['percent'] <=> (int) $b['percent'],
                default => (int) $a['remaining'] <=> (int) $b['remaining'],
            };
        });
        if ($order === 'desc') {
            $rows = array_reverse($rows);
        }

        $total = count($rows);
        $offset = max(0, (int) app('api_offset_value'));
        $limit = max(1, (int) app('api_limit_value'));
        $page = array_slice($rows, $offset, $limit);

        return (new LowStockTransformer)->transformLowStockItems($page, $total);
    }
}
