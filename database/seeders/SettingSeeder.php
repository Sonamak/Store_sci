<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'option' => 'whatsapp',
                'value' => null,
            ],
            [
                'option' => 'registration',
                'value' => 'on',
            ],
            [
                'option' => 'guest_allowed',
                'value' => 'on'
            ],
            [
                'option' => 'whatsapp_message',
                'value' => 'Hello User'
            ]
        ]);
    }
}
