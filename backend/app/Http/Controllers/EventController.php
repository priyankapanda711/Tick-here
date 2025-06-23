<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    //get all the events (admin)
    public function index()
    {
        $events = Event::orderBy("created_at", "desc")->paginate(10);
        return response()->json([
            'success' => true,
            'payload' => $events
        ], 200);
    }

    //get an event
    public function getEvent(Event $event)
    {
        return response()->json([
            'success' => true,
            'payload' => $event
        ]);
    }

    //Creates an event (admin)
    public function create()
    {
        try {
            $data = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'thumbnail' => 'required|image',
                'duration' => 'required',
                'category_id' => 'required|integer|exists:event_categories,id',
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

    //deletes an Event (admin)
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

    //update an event (admin)
    public function update(Request $request, Event $event)
    {
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found.'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'required|url',
            'duration' => 'required|integer',
            'category_id' => ['required', 'integer', Rule::exists('event_categories', 'id')],
        ]);

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully.',
            'data' => $event
        ], 200);
    }
}
