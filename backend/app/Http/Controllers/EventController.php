<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventVenue;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        // Eager loading (gives the eventVenue details along with the event details)
        $event = Event::with('eventVenue');

        return response()->json([
            'success' => true,
            'payload' => $event
        ]);
    }

    //Creates an event with multiple venues(admin)
    public function create()
    {
        try {
            $data = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'thumbnail' => 'required|image',
                'duration' => 'required|integer',
                'category_id' => 'required|integer|exists:event_categories,id',
                'venues' => 'required|array',
                'venues.*.location_id' => 'required|exists:locations,id',
                'venues.*.venue_id' => 'required|exists:venues,id',
                'venues.*.start_datetime' => 'required|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // handle image upload and store
        $data['thumbnail'] = request()->file('thumbnail')->store('thumbnails', 'public');

        // Save event
        $event = Event::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'thumbnail' => $data['thumbnail'],
            'duration' => $data['duration'],
            'category_id' => $data['category_id'],
            'admin_id' => auth('admin')->id(),
        ]);

        // Save related venues
        foreach ($data['venues'] as $venue) {
            EventVenue::create([
                'event_id' => $event->id,
                'location_id' => $venue['location_id'],
                'venue_id' => $venue['venue_id'],
                'start_datetime' => $venue['start_datetime'],
            ]);
        }

        if ($event) {
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully.',
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
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png',
            'duration' => 'required',
            'category_id' => ['required', 'integer', Rule::exists('event_categories', 'id')],
            'venues' => 'nullable|array',
            'venues.*.location_id' => 'required_with:venues|exists:locations,id',
            'venues.*.venue_id' => 'required_with:venues|exists:venues,id',
            'venues.*.start_datetime' => 'required_with:venues|date',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old if exists
            if ($event->thumbnail && Storage::disk('public')->exists($event->thumbnail)) {
                Storage::disk('public')->delete($event->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Update event
        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'thumbnail' => $validated['thumbnail'] ?? $event->thumbnail,
            'category_id' => $validated['category_id'],
        ]);

        // Update event venue(s) if provided
        if ($request->has('venues')) {
            // Optional: delete old event_venues and re-insert
            EventVenue::where('event_id', $event->id)->delete();

            foreach ($validated['venues'] as $venue) {
                EventVenue::create([
                    'event_id' => $event->id,
                    'location_id' => $venue['location_id'],
                    'venue_id' => $venue['venue_id'],
                    'start_datetime' => $venue['start_datetime'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully.',
            'data' => $event->load('eventVenues')
        ]);
    }

    // get events by a particular location
    public function getEventsByLocation(Location $location)
    {
        $eventVenues = $location->eventVenues()->with(['event.category', 'venue.seats'])->get();

        $events = [];

        foreach ($eventVenues as $ev) {
            $event = $ev->event;

            // Find lowest seat price from this venue
            $lowestPrice = $ev->venue->seats->min('price');

            $events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'thumbnail' => $event->thumbnail,
                'duration' => $event->duration,
                'category' => $event->category->name ?? null,
                'start_datetime' => $ev->start_datetime,
                'lowest_price' => $lowestPrice,
            ];
        }


        return response()->json(['data' => $events]);
    }

}
