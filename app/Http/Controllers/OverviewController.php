<?php

namespace App\Http\Controllers;

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
        $request = $client->get('https://api.geoapify.com/v1/geocode/search?text=' . $location . '&limit=1&apiKey=36540946f91a4e36996fd4e370d9225b');

        // Get Lon and Lat if request is success and has relevant data
        if ($request->getStatusCode() == 200 && count(json_decode($request->getBody())->features) !== 0) {
            $this->lon = json_decode($request->getBody())->features[0]->properties->lon;
            $this->lat = json_decode($request->getBody())->features[0]->properties->lat;
        }
    }

    public function index()
    {
        $bins = GarbageBin::all();

        foreach ($bins as $bin){
            $bin->last_active_at = date('d-m-Y, H:i', strtotime($bin->last_active_at));
        }

        return Inertia::render("Overview", [
            "bins"=> $bins
        ]);
    }

<<<<<<< HEAD
    public function showAddForm()
    {
        return Inertia::render("Overview/Add");
    }

    public function add(Request $request)
    {
        // Validate request
        $this->validateWithBag('addGarbageBin', $request, [
            'name' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string']
        ]);


        // Create bin
        $bin = Bin::create([
            'name' => $request['name'],
            'token' => Str::random(10),
            'distance' => rand(0, 10000)/100,
            'temperature' => (rand(0, 4000) - 1000)/100,
            'last_active_at' => time()
        ]);

        // Retrieve latitude and longitude
        $this->getLonLat($request);

        // Create location data
        Map::create([
            'garbage_bin_id' => $bin->id,
            'address' => $request['address'],
            'city' => $request['city'],
            'x' => $this->lon,
            'y' => $this->lat
        ]);

        return redirect(route('overview'));
    }

    public function showUpdateForm(Bin $bin)
    {
        return Inertia::render("Overview/Edit", [
            "bin" => $bin,
            "location" => Map::where("garbage_bin_id", $bin->id)->first()
=======
    public function showUpdateForm(GarbageBin $bin)
    {
        return Inertia::render("Overview/Edit", [
            "garbageBin" => $bin
>>>>>>> 9c0f76b4c106483c04d2e04cfd1d9e23394305e0
        ]);
    }

    public function update(Request $request, GarbageBin $bin)
    {
<<<<<<< HEAD
        // Validate request
=======
>>>>>>> 9c0f76b4c106483c04d2e04cfd1d9e23394305e0
        $this->validateWithBag('editGarbageBin', $request, [
            'name' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string']
        ]);

        // Update bin data
        $bin->update($request->only("name"));

<<<<<<< HEAD
        // Retrieve latitude and longitude
        $this->getLonLat($request);

        // Update location data if location data is relevant
        if ($this->lon !== null && $this->lat !== null) {
            Map::where("garbage_bin_id", $bin->id)->update([
                'address' => $request['address'],
                'city' => $request['city'],
                'x' => $this->lon,
                'y' => $this->lat
            ]);
        }
=======
        $bins = GarbageBin::all();
>>>>>>> 9c0f76b4c106483c04d2e04cfd1d9e23394305e0

        return redirect(route('overview'));
    }

    public function delete(GarbageBin $bin)
    {
<<<<<<< HEAD
        $map = Map::where("garbage_bin_id", $bin->id)->first();
        $map->delete();
        $bin->delete();

=======
        $bin->delete();

        $bins = GarbageBin::all();

>>>>>>> 9c0f76b4c106483c04d2e04cfd1d9e23394305e0
        return redirect(route('overview'));
    }
}
