<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    //get all the events
    public function index()
    {
        $events = Event::orderBy("created_at", "desc")->paginate(10);
        return response()->json([
            'success' => true,
            'payload' => $events
        ], 200);
    }

    //get an event by its Id
    public function getEvent(Event $event)
    {
        return response()->json([
            'success' => true,
            'payload' => $event
        ]);
    }

    //Creates an event
    public function store()
    {
        try {
            $data = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'thumbnail' => 'required|image',
                'start_date_time' => 'required|date',
                'end_date_time' => 'required|date|after_or_equal:start_date_time',
                'admin_id' => 'required|integer|exists:admins,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $data['thumbnail'] = request()->file('thumbnail')->store('thumbnails', 'public');

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

    //deletes an Event
    public function delete(Event $event)
    {
        $res = $event->delete();

        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Event deleted'
            ], 204);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 404);
        }
    }
}
