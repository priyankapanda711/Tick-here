<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\locations;
use Illuminate\Validation\ValidationException;
use Request;

class LocationController extends Controller
{
    //get all the locations(admin)
    public function index()
    {
        $location = Location::get();
        return response()->json([
            'success' => true,
            'message' => "location fetched",
            'data' => $location
        ]);
    }

    //create a location by admin
    public function create()
    {
        try {
            $data = request()->validate([
                "country" => "required|string",
                "state" => "required|string",
                "location_name" => "required|string"
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "errors" => $e->errors()
            ], 404);
        }

        $res = Location::create($data);

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => "Location Created"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Internal Server Error"
            ]);
        }
    }

    //delete a location (admin)
    public function delete(Location $location)
    {
        $res = $location->delete();

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Location deleted'
            ], 204);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 404);
        }
    }

    //update a location (admin)
    public function update(Request $request, Location $location)
    {
        if (!$location) {
                return response()->json([
                        'success' => false,
                        'message' => 'Location not found.'
                ], 404);
        }

        $validated = $request->validate([
                "country" => "required|string",
                "state" => "required|string",
                "location_name" => "required|string"
        ]);

        $location->update($validated);

        return response()->json([
                'success' => true,
                'message' => 'Location updated successfully.',
                'data' => $location
        ], 200);
    }
}
