<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bin;
use App\Models\Map;
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

    public function showUpdateForm(Bin $bin)
    {
        return Inertia::render("Overview/Edit", [
            "bin" => $bin
        ]);
    }

    public function update(Bin $bin, Request $request)
    {
        $this->validateWithBag('editGarbageBin', $request, [
            'name' => ['required', 'string'],
        ]);

        $bin->update($request->only("name"));
        
        $bins = Bin::all();

        return redirect(route('overview'));
    }

    public function delete(Bin $bin)
    {
        $map = Map::where("garbage_bin_id", $bin->id);
        $map->delete();
        $bin->delete();

        $bins = Bin::all();

        return redirect(route('overview'));
    }
}
