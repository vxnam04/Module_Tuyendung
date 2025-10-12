<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class JobPostAddresseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $jobPostId = DB::table('job_posts')->insertGetId([
            'teacher_id'           => 1,
            'job_title'            => $faker->jobTitle(),
            'company_name'         => $faker->company(),
            'description'          => $faker->sentence(15),
            'application_deadline' => $faker->dateTimeBetween('+1 week', '+1 month'),
            'created_at'           => Carbon::now(),
            'updated_at'           => Carbon::now(),
        ]);

        DB::table('job_post_addresses')->insert([
            'job_post_id' => $jobPostId,
            'street'      => $faker->streetAddress(),
            'city'        => $faker->randomElement(['Hà Nội', 'Đà Nẵng', 'Hồ Chí Minh', 'Cần Thơ']),
            'state'       => $faker->randomElement(['HN', 'HCM', 'DN', 'CT']),
            'country'     => 'VN',
            'postal_code' => $faker->postcode(),
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);
    }
}
