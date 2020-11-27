<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Map;
use App\Models\GarbageBin;
use Inertia\Inertia;

class MapController extends Controller
{
    public function index()
    {
        $bins = GarbageBin::with('latestValue')->get();

        foreach ($bins as $bin){
            $bin->last_active_at = date('d-m-Y, H:i', strtotime($bin->last_active_at));
        }

        return Inertia::render("Map", [
            "bins"=> $bins,
        ]);
    }
}
