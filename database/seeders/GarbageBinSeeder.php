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
        DB::table('garbage_bins')->insert([[
                "id" => 1,
                "token" => Str::random(10),
                "name" => "Garbage Bin 1",
                "address" => "Dageraadstraat 45",
                "city" => "Almere",
                "lat" => 52.408000,
                "lon" => 5.299960,
                "last_active_at" => date("2020-11-19 10:28:00")
            ], [
                "id" => 2,
                "token" => Str::random(10),
                "name" => "Garbage Bin 2",
                "address" => "Kiel 7",
                "city" => "Almere",
                "lat" => 52.388580,
                "lon" => 5.181260,
                "last_active_at" => date("2020-11-19 10:50:00")
            ], [
                "id" => 3,
                "token" => Str::random(10),
                "name" => "Garbage Bin 3",
                "address" => "Boylestraat 9",
                "city" => "Amsterdam",
                "lat" => 52.351730,
                "lon" => 4.940630,
                "last_active_at" => date("2020-11-19 15:19:00")
            ], [
                "id" => 4,
                "token" => Str::random(10),
                "name" => "Huis Snapking",
                "address" => "Laurence Olivierstraat 18",
                "city" => "Almere",
                "lat" => 52.370930,
                "lon" => 5.234530,
                "last_active_at" => date("2020-11-19 15:19:00")
            ]]
        );
    }
}
