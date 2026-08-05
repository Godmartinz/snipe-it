<?php

namespace Database\Seeders;

use App\Models\Setting;
use Database\Seeders\Concerns\ReportsMemory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use ReportsMemory;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->reportMemory('DatabaseSeeder start');

        // Only create default settings if they do not exist in the db.
        if (! Setting::first()) {
            // factory(Setting::class)->create();
            $this->call(SettingsSeeder::class);
        }

        $this->call(CompanySeeder::class);
        $this->reportMemory('after CompanySeeder');
        $this->call(CategorySeeder::class);
        $this->reportMemory('after CategorySeeder');
        $this->call(LocationSeeder::class);
        $this->reportMemory('after LocationSeeder');
        $this->call(DepartmentSeeder::class);
        $this->reportMemory('after DepartmentSeeder');
        $this->call(UserSeeder::class);
        $this->reportMemory('after UserSeeder');
        $this->call(DepreciationSeeder::class);
        $this->reportMemory('after DepreciationSeeder (1st)');
        $this->call(ManufacturerSeeder::class);
        $this->reportMemory('after ManufacturerSeeder');
        $this->call(SupplierSeeder::class);
        $this->reportMemory('after SupplierSeeder');
        // CustomFieldSeeder MUST run before AssetModelSeeder. Mobile-phone
        // model factories look up CustomFieldset by name (e.g., "Mobile
        // Devices") to attach — if the fieldsets aren't seeded yet, the
        // ?? fallback creates ad-hoc fieldsets that CustomFieldSeeder
        // then truncates, leaving the models pointing at orphan
        // fieldset_ids.
        $this->call(CustomFieldSeeder::class);
        $this->reportMemory('after CustomFieldSeeder');
        $this->call(AssetModelSeeder::class);
        $this->reportMemory('after AssetModelSeeder');
        $this->call(DepreciationSeeder::class);
        $this->reportMemory('after DepreciationSeeder (2nd)');
        $this->call(StatuslabelSeeder::class);
        $this->reportMemory('after StatuslabelSeeder');
        $this->call(AccessorySeeder::class);
        $this->reportMemory('after AccessorySeeder');
        $this->call(AssetSeeder::class);
        $this->reportMemory('after AssetSeeder');
        $this->call(LicenseSeeder::class);
        $this->reportMemory('after LicenseSeeder');
        $this->call(ComponentSeeder::class);
        $this->reportMemory('after ComponentSeeder');
        $this->call(ConsumableSeeder::class);
        $this->reportMemory('after ConsumableSeeder');
        $this->call(ActionlogSeeder::class);
        $this->reportMemory('after ActionlogSeeder');
        $this->call(MaintenanceSeeder::class);
        $this->reportMemory('after MaintenanceSeeder');

        // snipeit:sync-asset-locations used to run here to backfill location_id
        // on seeded assets. AssetFactory::configure() now sets location_id at
        // make-time based on the assignment state, so post-seed sync is
        // redundant. The command remains available as a manual maintenance
        // tool for production databases that need drift correction.

        Model::reguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call(ImportSeeder::class);
        $this->reportMemory('after ImportSeeder');
        DB::table('requested_assets')->truncate();

        $this->reportMemory('DatabaseSeeder end');
    }
}
