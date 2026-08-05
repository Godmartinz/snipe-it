<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\Concerns\ReportsMemory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AssetSeeder extends Seeder
{
    use ReportsMemory;

    private $admin;

    private $locationIds;

    private $supplierIds;

    public function run()
    {
        Asset::truncate();

        $this->ensureLocationsSeeded();
        $this->ensureSuppliersSeeded();

        $this->adminuser = User::where('permissions->superuser', '1')->first() ?? User::factory()->firstAdmin()->create();
        $this->locationIds = Location::all()->pluck('id');
        $this->supplierIds = Supplier::all()->pluck('id');

        $this->reportMemory('AssetSeeder start');

        // Chunk the big laptopMbp batch so we don't hold 2000 Asset models
        // (plus 2000 Actionlog observer side-effects) in memory at once, which
        // was pushing the demo servers into swap during full re-seeds.
        memory_reset_peak_usage();
        for ($i = 0; $i < 10; $i++) {
            Asset::factory()->count(200)->laptopMbp()->state(new Sequence($this->getState()))->create();
            gc_collect_cycles();
        }
        $this->reportMemory('AssetSeeder after laptopMbp chunked batch (2000 total)');

        memory_reset_peak_usage();
        Asset::factory()->count(50)->laptopMbpPending()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(50)->laptopMbpArchived()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(50)->laptopAir()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(50)->laptopSurface()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(5)->laptopXps()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(5)->laptopSpectre()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(50)->laptopZenbook()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(30)->laptopYoga()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(30)->desktopMacpro()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(30)->desktopLenovoI5()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(30)->desktopOptiplex()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(50)->confPolycom()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(20)->confPolycomcx()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(30)->tabletIpad()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(10)->tabletTab3()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(27)->phoneIphone11()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(40)->phoneIphone12()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(20)->ultrafine()->state(new Sequence($this->getState()))->create();
        Asset::factory()->count(20)->ultrasharp()->state(new Sequence($this->getState()))->create();

        $del_files = Storage::files('assets');
        foreach ($del_files as $del_file) { // iterate files
            Log::debug('Deleting: '.$del_files);
            try {
                Storage::disk('public')->delete('assets'.'/'.$del_files);
            } catch (\Exception $e) {
                Log::debug($e);
            }
        }

        DB::table('checkout_requests')->truncate();

        $this->reportMemory('AssetSeeder end (all factory batches complete)');
    }

    private function ensureLocationsSeeded()
    {
        if (! Location::count()) {
            $this->call(LocationSeeder::class);
        }
    }

    private function ensureSuppliersSeeded()
    {
        if (! Supplier::count()) {
            $this->call(SupplierSeeder::class);
        }
    }

    private function getState()
    {
        return function () {
            // Seeded assets are unassigned at creation, so location_id matches
            // rtd_location_id. Setting both here removes the need to run
            // snipeit:sync-asset-locations after seeding (that command exists
            // as a manual maintenance tool for production drift, not as a seed
            // dependency).
            $locationId = $this->locationIds->random();

            return [
                'rtd_location_id' => $locationId,
                'location_id' => $locationId,
                'supplier_id' => $this->supplierIds->random(),
                'created_by' => $this->adminuser->id,
            ];
        };
    }
}
