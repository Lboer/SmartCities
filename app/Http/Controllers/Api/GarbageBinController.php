<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GarbageBin;
use Illuminate\Http\Request;

class GarbageBinController extends Controller
{
    public function process($token, Request $request)
    {
        $this->validate($request, [
            'percentage_full' => ['required', 'integer', 'max:100', 'min:0'],
            'on_fire' => ['required', 'boolean'],
            'lat' => ['required'],
            'lon' => ['required']
        ]);

        if ($request->input('key') !== env('CLIENT_SECRET_KEY')) {
            return response()->json([
                'message' => 'Misschien moette gij gewoon die code meesture'
            ], 401);
        }

        GarbageBin::updateOrCreate(
            ['token' => $token],
            array_merge(
                $request->only('lat', 'lon', 'percentage_full', 'on_fire'),
                ['last_active_at' => now()]
            )
        );

        return response()->json([
            'success' => true
        ], 201);
    }
}
