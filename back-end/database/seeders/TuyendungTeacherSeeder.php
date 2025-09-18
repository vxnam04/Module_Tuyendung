<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TuyendungTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo teacher ID trùng với JWT sub = 2
        DB::table('teachers')->updateOrInsert(
            ['lecturer_id' => 2], // ID từ JWT
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Teacher with lecturer_id = 2 seeded!');
    }
}
