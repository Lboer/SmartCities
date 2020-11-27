<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GarbageBinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('garbage_bin')->insert([[
            "id" => 1,
            "token" => Str::random(10),
            "name" => "Garbage Bin 1",
            "lat" => 52.3507849,
            "lon" => 5.2647016,
            "percentage_full" => 37,
            "on_fire" => 20,
            "last_active_at" => date("2020-11-19 10:28:00")
        ],[
            "id" => 2,
            "token" => Str::random(10),
            "name" => "Garbage Bin 2",
            "lat" => 52.3508492,
            "lon" => 5.6647016,
            "percentage_full" => 92,
            "on_fire" => 30,
            "last_active_at" => date("2020-11-19 10:50:00")
        ],[
            "id" => 3,
            "token" => Str::random(10),
            "name" => "Garbage Bin 3",
            "lat" => 52.3507149,
            "lon" => 5.2647516,
            "percentage_full" => 100,
            "on_fire" => 84,
            "last_active_at" => date("2020-11-19 15:19:00")
        ]]
    );
    }
}
