<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\MaintenanceType;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Maintenance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $maintenanceType = MaintenanceType::factory()->create();

        return [
            // Set location_id to rtd_location_id on the generated asset so
            // seeded maintenance rows point at assets with a real location,
            // matching what snipeit:sync-asset-locations would have set.
            'asset_id' => Asset::factory()->laptopZenbook()->afterMaking(function (Asset $asset) {
                if ($asset->location_id === null) {
                    $asset->location_id = $asset->rtd_location_id;
                }
            }),
            'supplier_id' => Supplier::factory(),
            'maintenance_type_id' => $maintenanceType->id,
            'asset_maintenance_type' => $maintenanceType->name,
            'name' => $this->faker->sentence(3),
            'start_date' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'is_warranty' => $this->faker->boolean(),
            'notes' => $this->faker->paragraph(),
            'url' => $this->faker->url(),
            'cost' => $this->faker->randomFloat(),
            'created_by' => User::factory()->superuser(),
            'image' => $this->faker->numberBetween(1, 11).'.png',
        ];
    }
}
