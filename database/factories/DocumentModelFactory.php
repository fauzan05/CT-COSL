<?php

namespace Database\Factories;

use App\Models\DocumentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentModelFactory extends Factory
{
    protected $model = DocumentModel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'menu' => fake()->randomElement(['coiled_tubing', 'nitrogen']),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}