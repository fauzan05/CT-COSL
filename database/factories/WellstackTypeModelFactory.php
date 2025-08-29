<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WellstackTypeModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WellstackTypeModelFactory extends Factory
{
    protected $model = WellstackTypeModel::class;

    public function definition(): array
    {
        $name = fake()->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}