<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('values')->insert([[
                "garbage_bin_id" => 1,
                "percentage_full" => 37,
                "on_fire" => 0,
            ], [
                "garbage_bin_id" => 2,
                "percentage_full" => 92,
                "on_fire" => 0,
            ], [
                "garbage_bin_id" => 3,
                "percentage_full" => 76,
                "on_fire" => 1,
            ], [
                "garbage_bin_id" => 4,
                "percentage_full" => 100,
                "on_fire" => 0,
            ]]
        );
    }
}
