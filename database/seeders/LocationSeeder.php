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
            "x" => 27.50,
            "y" => 30.75
        ],[
            "id" => 2,
            "garbage_bin_id" => 2,
            "x" => 93.12,
            "y" => 75.41
        ],[
            "id" => 3,
            "garbage_bin_id" => 3,
            "x" => 67.37,
            "y" => 88.42
        ]]
    );
    }
}
