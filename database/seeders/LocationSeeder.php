<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('location')->insert([[
            "id" => 1,
            "garbage_bin_id" => 1,
            "x" => 5.219310,
            "y" => 52.368170
        ],[
            "id" => 2,
            "garbage_bin_id" => 2,
            "x" => 5.192910,
            "y" => 52.363360
        ],[
            "id" => 3,
            "garbage_bin_id" => 3,
            "x" => 5.181260,
            "y" => 52.388580
        ]]
    );
    }
}
