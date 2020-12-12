<?php

namespace App\Http\Controllers;

use App\Models\Value;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;
use App\Models\GarbageBin;
use App\Models\Map;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OverviewController extends Controller
{
    // initiate lon and lat
    private $lon = null;
    private $lat = null;

    public function getLonLat($request)
    {
        // Call GeoApify API
        $location = $request['address'] . ' ' . $request['city'];
        $client = new GuzzleHTTP\Client();
        $request = $client->get('https://api.geoapify.com/v1/geocode/search?text=' . $location . '&limit=1&apiKey='.env('GEOAPIFY_KEY'));

        // Get Lon and Lat if request is success and has relevant data
        if ($request->getStatusCode() == 200 && count(json_decode($request->getBody())->features) !== 0) {
            $this->lon = json_decode($request->getBody())->features[0]->properties->lon;
            $this->lat = json_decode($request->getBody())->features[0]->properties->lat;
        }
    }

    public function showAddForm()
    {
        return Inertia::render("Overview/Add");
    }

    public function showBin(GarbageBin $bin)
    {
        return Inertia::render("Overview/View", [
            "bin" => $bin,
            "records" => $bin->values()->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function add(Request $request)
    {
        //Validate request
        $request->validateWithBag('addGarbageBin', [
            'name' => ['required', 'string', 'max:100', 'min:1'],
            'address' => ['required', 'string', 'max:100', 'min:1'],
            'city' => ['required', 'string', 'max:100', 'min:1'],
        ]);

        //Make API call for location
        $location = $request['address'] . ' ' . $request['city'] . ' ';
        $client = new GuzzleHttp\Client();
        $api = $client->get('https://api.geoapify.com/v1/geocode/search?text=' . $location . '&limit=1&apiKey='.env('GEOAPIFY_KEY'));

        //If API returns valuable location data create bin with location data otherwise create bin without location data
        if ($api->getStatusCode() == 200 && count(json_decode($api->getBody())->features) !== 0) {
            $createdBin = GarbageBin::create([
                'token' => Str::random(10),
                'lon' => json_decode($api->getBody())->features[0]->properties->lon,
                'lat' => json_decode($api->getBody())->features[0]->properties->lat,
                'address' => $request['address'],
                'city' => $request['city'],
                'name' => $request['name']
            ]);
        } else {
            $createdBin = GarbageBin::create([
                'token' => Str::random(10),
                'name' => $request['name']
                ]);
        }

        //Create first value entry
        Value::create([
           'garbage_bin_id' => $createdBin->id,
           'on_fire' => 0,
           'percentage_full' => 0
        ]);

        return redirect(route('overview'));
    }

    public function index()
    {
        $bins = GarbageBin::with('latestValue')->get();

        foreach ($bins as $bin) {
            $bin->last_active_at = date('d-m-Y, H:i', strtotime($bin->last_active_at));
        }

        return Inertia::render("Overview", [
            "bins" => $bins
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
        $request->validateWithBag('editGarbageBin', [
            'name' => ['required', 'string', 'max:100', 'min:1'],
            'address' => ['required', 'string', 'max:100', 'min:1'],
            'city' => ['required', 'string', 'max:100', 'min:1'],
        ]);

        $bin->name = $request['name'];
        if ($bin->address !== $request['address'] || $bin->city !== $request['city']) {
            $location = $request['address'] . ' ' . $request['city'] . ' ';
            $client = new GuzzleHttp\Client();
            $api = $client->get('https://api.geoapify.com/v1/geocode/search?text=' . $location . '&limit=1&apiKey=36540946f91a4e36996fd4e370d9225b');
            if ($api->getStatusCode() == 200 && count(json_decode($api->getBody())->features) !== 0) {
                $bin->lon = json_decode($api->getBody())->features[0]->properties->lon;
                $bin->lat = json_decode($api->getBody())->features[0]->properties->lat;
                $bin->address = $request['address'];
                $bin->city = $request['city'];
            }
        }
        $bin->save();

        return redirect(route('overview'));
    }

    public function delete(GarbageBin $bin)
    {
        $bin->delete();

        $bins = GarbageBin::all();

        return redirect(route('overview'));
    }
}
