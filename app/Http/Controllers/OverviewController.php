<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GarbageBin;
use App\Models\Map;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function index()
    {
        $bins = GarbageBin::all();

        return Inertia::render("Overview", [
            "bins"=> $bins
        ]);
    }

    public function showUpdateForm(GarbageBin $bin)
    {
        return Inertia::render("Overview/Edit", [
            "garbageBin" => $bin
        ]);
    }

    public function update(Request $request, GarbageBin $bin)
    {
        $this->validateWithBag('editGarbageBin', $request, [
            'name' => ['required', 'string'],
        ]);

        $bin->update($request->only("name"));

        $bins = GarbageBin::all();

        return redirect(route('overview'));
    }

    public function delete(GarbageBin $bin)
    {
        $bin->delete();

        $bins = GarbageBin::all();

        return redirect(route('overview'));
    }
}
