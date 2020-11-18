<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bin;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function index()
    {
        $bins = Bin::all();

        return Inertia::render("Overview", [
            "bins"=> $bins
        ]);
    }
}
