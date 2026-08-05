<?php

namespace Database\Seeders;

use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Database\Seeders\Concerns\ReportsMemory;
use Illuminate\Database\Seeder;

class ActionlogSeeder extends Seeder
{
    use ReportsMemory;

    public function run()
    {
        Actionlog::truncate();

        if (! Asset::count()) {
            $this->call(AssetSeeder::class);
        }

        if (! Location::count()) {
            $this->call(LocationSeeder::class);
        }

        $admin = User::where('permissions->superuser', '1')->first() ?? User::factory()->firstAdmin()->create();

        $this->reportMemory('ActionlogSeeder start');

        memory_reset_peak_usage();
        Actionlog::factory()
            ->count(300)
            ->assetCheckoutToUser()
            ->create(['created_by' => $admin->id]);
        gc_collect_cycles();
        $this->reportMemory('ActionlogSeeder after 300 assetCheckoutToUser');

        memory_reset_peak_usage();
        Actionlog::factory()
            ->count(100)
            ->assetCheckoutToLocation()
            ->create(['created_by' => $admin->id]);
        gc_collect_cycles();
        $this->reportMemory('ActionlogSeeder after 100 assetCheckoutToLocation');

        memory_reset_peak_usage();
        Actionlog::factory()
            ->count(20)
            ->licenseCheckoutToUser()
            ->create(['created_by' => $admin->id]);
        gc_collect_cycles();
        $this->reportMemory('ActionlogSeeder after 20 licenseCheckoutToUser');
    }
}
