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
            'asset_id' => Asset::factory()->laptopZenbook(),
            'supplier_id' => Supplier::factory(),
            'maintenance_type_id' => $maintenanceType->id,
            'asset_maintenance_type' => $maintenanceType->name,
            'name' => $this->faker->sentence(3),
            'start_date' => $this->faker->date(),
            'is_warranty' => $this->faker->boolean(),
            'notes' => $this->faker->paragraph(),
            'url' => $this->faker->url(),
            'cost' => $this->faker->randomFloat(),
            'created_by' => User::factory()->superuser(),
            'image' => $this->faker->numberBetween(1, 11).'.png',
        ];
    }
}
