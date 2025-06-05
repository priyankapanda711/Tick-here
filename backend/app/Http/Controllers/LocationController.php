<?php

namespace App\Http\Controllers;

use App\Models\locations;
use Illuminate\Validation\ValidationException;

class LocationController extends Controller
{
    //
    public function index()
    {
        $cities = locations::get();
        return response()->json([
            'success' => true,
            'message' => "Cities fetched",
            'data' => $cities
        ]);
    }
    public function create()
    {
        try {
            $data = request()->validate([
                "country" => "required|string",
                "state" => "required|string",
                "city" => "required|string"
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "errors" => $e->errors()
            ], 404);
        }

        $res = locations::create($data);

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => "City Created"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Internal Server Error"
            ]);
        }
    }
}
