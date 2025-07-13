<?php

namespace Database\Seeders;

use App\Models\ThreadModel;
use Illuminate\Database\Seeder;

class Thread extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $threads = [
            ['type' => 'AMMT', 'created_by' => 1, 'updated_by' => 1],
            ['type' => 'NOWSCO', 'created_by' => 1, 'updated_by' => 1],
            ['type' => 'PAC', 'created_by' => 1, 'updated_by' => 1],
        ];

        ThreadModel::insert($threads);

        $thread_sizes = [
            ['thread_id' => 1, 'top_connection' => '2-7/8" EUE', 'bottom_connection' => '2-7/8" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['thread_id' => 1, 'top_connection' => '3-1/2" EUE', 'bottom_connection' => '3-1/2" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['thread_id' => 2, 'top_connection' => '2-7/8" EUE', 'bottom_connection' => '2-7/8" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['thread_id' => 2, 'top_connection' => '3-1/2" EUE', 'bottom_connection' => '3-1/2" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['thread_id' => 3, 'top_connection' => '2-7/8" EUE', 'bottom_connection' => '2-7/8" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['thread_id' => 3, 'top_connection' => '3-1/2" EUE', 'bottom_connection' => '3-1/2" EUE', 'created_by' => 1, 'updated_by' => 1],
        ];

        foreach ($thread_sizes as $size) {
            $size['created_at'] = now();
            $size['updated_at'] = now();
            ThreadModel::find($size['thread_id'])->sizes()->create($size);
        }
    }
}
