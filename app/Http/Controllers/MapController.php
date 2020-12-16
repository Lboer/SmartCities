<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;
use App\Models\Map;
use App\Models\GarbageBin;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class MapController extends Controller
{
    public function index()
    {
        $bins = GarbageBin::with('latestValue')->get();

        foreach ($bins as $bin) {
            $bin->last_active_at = date('d-m-Y, H:i', strtotime($bin->last_active_at));
        }

        return Inertia::render("Map", [
            "bins" => $bins,
        ]);
    }

    public function determineRoute(Request $request)
    {
        //Validate request
        $request->validate([
            'fullness' => ['required', 'integer', 'min:0', 'max:100']
        ]);

        //Get al bins that meet requirements
        $bins = GarbageBin::whereNotNull('lat')->whereNotNull('lon')->whereHas('latestValue', function ($q) use ($request) {
            $q->where('percentage_full', '>=', $request['fullness']);
        })->get();

        //Create shipments object for fastest route API call
        $shipments = [];
        foreach ($bins as $bin) {
            array_push($shipments,
                [
                    'pickup' => [
                        'location' => [$bin->lon, $bin->lat],
                        'duration' => 120
                    ],
                    'delivery' => [
                        'location_index' => 0,
                        'duration' => 120
                    ]
                ]);
        }

        //Create body object for fastest route API call
        $body = [
            'mode' => 'drive',
            'agents' => [
                [
                    'start_location' => [5.2752231, 52.3352174],
                    'end_location' => [5.2752231, 52.3352174],
                ]
            ],
            'shipments' => $shipments,
            'locations' => [
                [
                    'id' => 'dump',
                    'location' => [5.2752231, 52.3352174]
                ]
            ]
        ];

        //Make API call and get necessary data for fastest route
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.geoapify.com/v1/routeplanner?apiKey=' . env('GEOAPIFY_KEY'), $body);

        $response = $response->json();

        if (!$response || !isset($response['features'])) {
            return redirect()->route('map')->withErrors([
                'error' => 'Geen prullenbakken met deze criteria gevonden'
            ]);
        }

        $waypoints = $response['features'][0]['properties']['waypoints'];
        $coordinates = [];
        foreach ($waypoints as $waypoint) {
            array_push($coordinates, $waypoint['original_location'][1] . ',' . $waypoint['original_location'][0]);
        }
        $coordinates = implode('|', $coordinates);

        //Use the order of coordinates to make the route description and get route data
        $client = new GuzzleHTTP\Client();
        $response = $client->get('https://api.geoapify.com/v1/routing?waypoints=' . $coordinates . '&mode=drive&apiKey=' . env('GEOAPIFY_KEY'));
        $routeData = json_decode($response->getBody())->features[0];

        //Delete the route if it already exists and create a new one
        $route = Route::latest();
        if ($route !== null) {
            $route->delete();
        }
        Route::create([
            'route_data' => serialize($routeData),
            'fullness' => $request['fullness']
        ]);

        //Redirect to the route page
        return redirect()->route('route');
    }

    public function route()
    {
        $route = Route::latest()->first();

        if ($route !== null) {
            return Inertia::render("Map/Route", [
                "routeData" => unserialize($route->route_data),
                "fullness" => $route->fullness
            ]);
        } else {
            return redirect()->route('map');
        }
    }
}
