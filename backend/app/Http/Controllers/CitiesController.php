<?php

namespace App\Http\Controllers;

use App\Models\cities;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CitiesController extends Controller
{
    //
    public function create()
    {
        try {
            $data = request()->validate([
                "country" => "required|string",
                "state" => "required|string",
                "city" => "required|string",
                "event_id" => ["required", "integer", Rule::exists('events', 'id')]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "errors" => $e->errors()
            ], 404);
        }

        $res = cities::create($data);

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
