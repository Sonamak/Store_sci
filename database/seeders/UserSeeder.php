<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@' . env('APP_DOMAIN'),
            'phone' => 1234567890,
            'city' => 'Habra',
            'educational_attainment_id' => 1,
            'general_specialization_id' => 3,
            'specialization_id' => 4,
            'password' => Hash::make('admin123'),
            'api_token' => Str::random(60),
            'email_verified_at' => Carbon::now(),
            'role' => 'admin',
            'locale' => 'en_US',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
