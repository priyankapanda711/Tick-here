<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    //
    public function getEvent(Event $event)
    {
        return response()->json([
            'success' => true,
            'payload' => $event
        ]);
    }

    public function store()
    {
        try {
            $data = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'start_date_time' => 'required|date',
                'end_date_time' => 'required|date|after_or_equal:start_date_time',
                'admin_id' => 'required|integer|exists:admins,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $event = Event::create($data);

        if ($event) {
            return response()->json([
                'success' => true,
                'message' => 'Event created',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error while creating a event',
            ], 404);
        }
    }
}
