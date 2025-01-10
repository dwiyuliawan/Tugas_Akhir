<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'setting_id' => 1,
            'company_name' => 'Grosirs',
            'address' => 'Jl. Ra Kartini Ketanggugan Ds. Dukuh Tengah',
            'phone_number' => '081234779987',
            'type_nota' => 1, // kecil
            'discount' => 5,
            'path_logo' => '/img/logo2.png',
            'path_member_card' => '/img/member.png',
        ]);
    }
}
