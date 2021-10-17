<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $path = storage_path('sql/countries.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
