<?php

namespace Database\Factories;

use App\Models\MaintenanceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceTypeFactory extends Factory
{
    protected $model = MaintenanceType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
        ];
    }
}
