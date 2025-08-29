<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WellstackItemModel;
use App\Models\WellstackTypeModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class WellstackItemModelFactory extends Factory
{
    protected $model = WellstackItemModel::class;

    public function definition(): array
    {
        return [
            'wellstack_type_id' => WellstackTypeModel::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'serial_number' => fake()->bothify('WS-####-???'),
            'image' => 'default-item.jpg',
            'height' => fake()->randomFloat(2, 1, 100),
            'height_unit' => fake()->randomElement(['ft', 'm', 'in']),
            'weight' => fake()->randomFloat(2, 10, 1000),
            'weight_unit' => fake()->randomElement(['lbs', 'kg', 'tons']),
            'pressure_rating' => fake()->randomFloat(2, 100, 5000),
            'pressure_rating_unit' => fake()->randomElement(['psi', 'bar', 'kPa']),
            'owner' => fake()->company(),
            'shear_ram_dist_from_bottom' => fake()->randomFloat(2, 1, 50),
            'shear_ram_dist_from_bottom_unit' => fake()->randomElement(['ft', 'm', 'in']),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}