<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teachers')->insert([
            [
                'lecturer_id' => 1,
                'avatar_url' => 'https://example.com/avatar1.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lecturer_id' => 2,
                'avatar_url' => 'https://example.com/avatar2.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lecturer_id' => 3,
                'avatar_url' => 'https://example.com/avatar3.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
