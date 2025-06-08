<?php

namespace App\Http\Controllers;

use App\Models\venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VenueController extends Controller
{
    //get all the venues (admin)
    public function index()
    {
        $venue = Venue::get();
        return response()->json([
            'success' => true,
            'message' => "venue fetched",
            'data' => $venue
        ]);
    }

    //create a venue (admin)
    public function create()
    {
        try {
            $data = request()->validate([
                "venue_name" => "required",
                "location_id" => ['required', Rule::exists('locations', 'id')],
                "available_seats" => "required|min:2"
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 404);
        }

        $res = Venue::create($data);

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => "Venue Created"
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Internal Server Error"
            ], 404);
        }
    }
}
