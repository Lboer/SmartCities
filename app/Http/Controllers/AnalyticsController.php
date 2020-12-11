<?php

namespace App\Http\Controllers;

use App\Models\GarbageBin;
use App\Models\Value;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function index()
    {
        return Inertia::render('Analytics', [
            "bins" => GarbageBin::all()
        ]);
    }
}
