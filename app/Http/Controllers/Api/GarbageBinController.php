<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GarbageBin;
use App\Models\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GarbageBinController extends Controller
{
    public function process($token, Request $request)
    {
        $this->validate($request, [
            'percentage_full' => ['required', 'integer', 'max:100', 'min:0'],
            'on_fire' => ['required', 'boolean'],
        ]);

        if ($request->input('key') !== env('CLIENT_SECRET_KEY')) {
            return response()->json([
                'message' => 'Misschien moette gij gewoon die code meesture'
            ], 401);
        }

        $garbageBin = GarbageBin::where('token', $token)->first();

        $updatedGarbageBin = GarbageBin::updateOrCreate(
            ['token' => $token],
            ['name' => $garbageBin->name ?? Str::random(10), 'last_active_at' => now()]

        );

        Value::create([
            'garbage_bin_id' => $updatedGarbageBin->id,
            'percentage_full' => $request['percentage_full'],
            'on_fire' => $request['on_fire']
        ]);

        return response()->json([
            'success' => true
        ], 201);
    }

    public function getAnalytics($bin, Request $request)
    {
        $data = Value::where("garbage_bin_id", "=", $bin)->get();
        return $data;
    }
}
