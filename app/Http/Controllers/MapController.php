<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Map;
use App\Models\Bin;
use Inertia\Inertia;

class MapController extends Controller
{
    public function index()
    {
        $locations = Map::all();
        $bins = Bin::all();

        return Inertia::render("Map", [
            "locations"=> $locations,
            "bins" => $bins
        ]);
    }
}
