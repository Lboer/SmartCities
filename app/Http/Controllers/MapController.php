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
        $locations = Map::with('bin')->get();

        foreach ($locations as $location){
            $location->bin->last_active_at = date('d-m-Y, H:i', strtotime($location->bin->last_active_at));
        }

        return Inertia::render("Map", [
            "locations"=> $locations,
        ]);
    }
}
