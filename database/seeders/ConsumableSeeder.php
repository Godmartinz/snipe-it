<?php

namespace Database\Seeders;

use App\Models\Actionlog;
use App\Models\Consumable;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsumableSeeder extends Seeder
{
    public function run()
    {
        // See AssetModelSeeder for the stale-action_log rationale.
        Actionlog::where('item_type', Consumable::class)->delete();
        Consumable::truncate();
        DB::table('consumables_users')->truncate();

        if (! Supplier::count()) {
            $this->call(SupplierSeeder::class);
        }
        $supplierIds = Supplier::all()->pluck('id');

        $admin = User::where('permissions->superuser', '1')->first() ?? User::factory()->firstAdmin()->create();

        // See AccessorySeeder for the withInitialAcquisition rationale —
        // fills demo Order + OrderItem rows with realistic supplier /
        // cost / date so the index page and info-panel don't render
        // empty "Last Unit Cost" / "Last Purchase Date" columns.
        $randomAcquisition = function () use ($supplierIds) {
            $supplier = Supplier::find($supplierIds->random());
            $cost = fake()->randomFloat(2, 1, 50);
            $date = Carbon::instance(fake()->dateTimeBetween('-1 years', 'now'))->toDateString();

            return compact('supplier', 'cost', 'date');
        };

        foreach (['cardstock', 'paper', 'ink'] as $state) {
            $acq = $randomAcquisition();
            Consumable::factory()
                ->{$state}()
                ->withInitialAcquisition($acq['supplier'], $acq['cost'], $acq['date'])
                ->create([
                    'default_supplier_id' => $acq['supplier']->id,
                    'created_by' => $admin->id,
                ]);
        }

        // Check out a couple of each consumable to random users so the
        // view page doesn't render as an empty checkout list.
        $checkoutTargets = User::where('activated', 1)
            ->where('show_in_list', '!=', '0')
            ->inRandomOrder()
            ->limit(6)
            ->get();
        foreach (Consumable::all() as $consumable) {
            foreach ($checkoutTargets->random(min(rand(2, 4), $checkoutTargets->count())) as $user) {
                $consumable->users()->attach($user->id, [
                    'created_by' => $admin->id,
                ]);
            }
        }
    }
}
