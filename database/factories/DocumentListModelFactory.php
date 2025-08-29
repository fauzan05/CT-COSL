<?php

namespace Database\Factories;

use App\Models\DocumentListModel;
use App\Models\DocumentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentListModelFactory extends Factory
{
    protected $model = DocumentListModel::class;

    public function definition(): array
    {
        return [
            'document_id' => DocumentModel::factory(),
            'filename' => fake()->uuid() . '.' . fake()->randomElement(['pdf', 'doc', 'docx', 'jpg', 'png']),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}