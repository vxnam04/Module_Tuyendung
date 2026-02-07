<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [];

        for ($i = 0; $i <= 30; $i++) {
            $teachers[] = [
                'lecturer_id' => 2 + $i,
                'avatar_url' => 'https://example.com/avatar' . (2 + $i) . '.png',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('teachers')->insert($teachers);
    }
}
