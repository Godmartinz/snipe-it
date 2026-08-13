<?php

namespace Tests\Feature\Assets;

use App\Models\Asset;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;

/**
 * AssetObserver::created writes a matching Order + OrderItem for every
 * new asset regardless of how the asset arrived (web form, API, CSV
 * importer). Dedupe semantics match the adjust-quantity flow: a
 * populated order_number shares the Order row with same-tuple assets,
 * a blank one gets its own Order per event.
 */
class AssetObserverOrderTest extends TestCase
{
    public function test_asset_create_with_order_number_writes_matching_order_and_order_item(): void
    {
        $actor = User::factory()->createAssets()->create();
        $supplier = Supplier::factory()->create();

        $asset = Asset::factory()->create([
            'created_by' => $actor->id,
            'company_id' => $actor->company_id,
            'supplier_id' => $supplier->id,
            'order_number' => 'PO-OBS-1',
            'purchase_date' => '2026-04-01',
            'purchase_cost' => 199.99,
        ]);

        $line = OrderItem::where('item_type', Asset::class)
            ->where('item_id', $asset->id)
            ->firstOrFail();

        $this->assertSame(1, (int) $line->qty);
        $this->assertEquals(199.99, (float) $line->price);

        $order = Order::findOrFail($line->order_id);
        $this->assertSame('PO-OBS-1', $order->order_number);
        $this->assertSame($supplier->id, (int) $order->supplier_id);
        $this->assertSame($actor->company_id, $order->company_id);
        $this->assertSame('2026-04-01', $order->purchase_date->toDateString());
    }

    public function test_two_assets_with_same_order_tuple_share_one_order_row(): void
    {
        $actor = User::factory()->createAssets()->create();
        $supplier = Supplier::factory()->create();

        $shared = [
            'created_by' => $actor->id,
            'company_id' => $actor->company_id,
            'supplier_id' => $supplier->id,
            'order_number' => 'PO-OBS-SHARED',
            'purchase_date' => '2026-04-02',
        ];

        $first = Asset::factory()->create($shared + ['purchase_cost' => 100]);
        $second = Asset::factory()->create($shared + ['purchase_cost' => 200]);

        $orders = Order::where('order_number', 'PO-OBS-SHARED')
            ->where('supplier_id', $supplier->id)
            ->where('company_id', $actor->company_id)
            ->get();

        $this->assertCount(1, $orders, 'Same (order_number, supplier, company) tuple must dedupe.');

        $lineIds = OrderItem::where('item_type', Asset::class)
            ->whereIn('item_id', [$first->id, $second->id])
            ->pluck('order_id')
            ->unique()
            ->values();

        $this->assertCount(1, $lineIds);
        $this->assertSame($orders->first()->id, (int) $lineIds->first());
    }

    public function test_blank_order_number_creates_a_distinct_order_per_asset(): void
    {
        $actor = User::factory()->createAssets()->create();
        $supplier = Supplier::factory()->create();
        $ordersBefore = Order::count();

        $shared = [
            'created_by' => $actor->id,
            'company_id' => $actor->company_id,
            'supplier_id' => $supplier->id,
            'order_number' => null,
        ];

        Asset::factory()->create($shared);
        Asset::factory()->create($shared);

        // Blank order_number = distinct transaction, so both assets get
        // their own Order rows even though supplier + company match.
        $this->assertSame($ordersBefore + 2, Order::count());
    }
}
