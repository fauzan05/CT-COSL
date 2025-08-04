<?php

namespace Database\Seeders;

use App\Models\ThreadModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Thread extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $thread_1 = Str::uuid();
        $thread_2 = Str::uuid();
        $thread_3 = Str::uuid();
        $threads = [
            ['id' => $thread_1, 'type' => 'AMMT', 'created_by' => 1, 'updated_by' => 1],
            ['id' => $thread_2, 'type' => 'NOWSCO', 'created_by' => 1, 'updated_by' => 1],
            ['id' => $thread_3, 'type' => 'PAC', 'created_by' => 1, 'updated_by' => 1],
        ];

        ThreadModel::insert($threads);

        $thread_sizes = [
            ['id' => Str::uuid(), 'thread_id' => $thread_1, 'top_connection' => '2-7/8" EUE', 'bottom_connection' => '2-7/8" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['id' => Str::uuid(), 'thread_id' => $thread_1, 'top_connection' => '3-1/2" EUE', 'bottom_connection' => '3-1/2" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['id' => Str::uuid(), 'thread_id' => $thread_2, 'top_connection' => '2-7/8" EUE', 'bottom_connection' => '2-7/8" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['id' => Str::uuid(), 'thread_id' => $thread_2, 'top_connection' => '3-1/2" EUE', 'bottom_connection' => '3-1/2" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['id' => Str::uuid(), 'thread_id' => $thread_3, 'top_connection' => '2-7/8" EUE', 'bottom_connection' => '2-7/8" EUE', 'created_by' => 1, 'updated_by' => 1],
            ['id' => Str::uuid(), 'thread_id' => $thread_3, 'top_connection' => '3-1/2" EUE', 'bottom_connection' => '3-1/2" EUE', 'created_by' => 1, 'updated_by' => 1],
        ];

        foreach ($thread_sizes as $size) {
            $size['created_at'] = now();
            $size['updated_at'] = now();
            ThreadModel::find($size['thread_id'])->sizes()->create($size);
        }
    }
}
