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
            "distance" => 40.8,
            "temperature" => 28,
            "last_active_at" => date("2020-11-19 10:28:00")
        ],[
            "id" => 2,
            "token" => Str::random(10),
            "name" => "Garbage Bin 2",
            "distance" => 23.8,
            "temperature" => 19.8,
            "last_active_at" => date("2020-11-19 12:40:00")
        ],[
            "id" => 3,
            "token" => Str::random(10),
            "name" => "Garbage Bin 3",
            "distance" => 5.7,
            "temperature" => 23.8,
            "last_active_at" => date("2020-11-19 15:00:00")
        ]]
    );
    }
}
