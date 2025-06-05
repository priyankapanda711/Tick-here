<?php

namespace App\Http\Controllers;

use App\Models\venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VenueController extends Controller
{
    //
    public function create()
    {
        try {
            $data = request()->validate([
                "building_name" => "required",
                "city_id" => ['required', Rule::exists('locations', 'id')],
                "available_seats" => "required|min:2"
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 404);
        }

        $res = venue::create($data);

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => "Location Created"
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Internal Server Error"
            ], 404);
        }
    }
}
