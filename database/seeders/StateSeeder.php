<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $path = storage_path('sql/country-states.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
