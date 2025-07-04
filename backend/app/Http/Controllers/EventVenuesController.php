<?php

namespace App\Http\Controllers;

use App\Models\EventVenue;
use Illuminate\Http\Request;

class EventVenuesController extends Controller
{
    //get all the venues for an event and give the structured data in json format as response
    public function getVenuesByEvent($event)
    {
        $venues = EventVenue::with(['location', 'venue'])
            ->where('event_id', $event->id)
            ->get()
            ->groupBy('location_id')
            ->map(function ($group) {
                $location = $group->first()->location;

                return [
                    'location_id' => $location->id,
                    'city' => $location->city,
                    'state' => $location->state,
                    'country' => $location->country,
                    'venues' => $group->map(function ($ev) {
                        return [
                            'venue_id' => $ev->venue->id,
                            'venue_name' => $ev->venue->venue_name,
                            'available_seats' => $ev->available_seats,
                            'start_datetime' => $ev->start_datetime
                        ];
                    })->values()
                ];
            })->values(); // to re-index keys

        return response()->json([
            'success' => true,
            'data' => $venues
        ]);
    }

    //get all the tickets for an event (admin only)
    public function getTicketsByEvent($eventId)
    {

    }

    public function search(Request $request)
 {
    $category = $request->input('category');
    $city = $request->input('city');
    $queryTerm = $request->input('query');
    $sort = $request->input('sort'); // 'name' or 'date'

    //  load necessary relationships
    $query = Event::query()->with(['category', 'admin', 'eventVenue.location']);

    // Filter by category
    if ($category) {
    $query->whereHas('category', function ($q) use ($category) {
    $q->where('name', 'like', '%' . $category . '%');
    });
    }

    // Filter by city
    if ($city) {
    $query->whereHas('eventVenue.location', function ($q) use ($city) {
    $q->where('location_name', 'like', '%' . $city . '%');
    });
    }

    // Apply sorting
    if ($sort === 'name') {
    $query->orderBy('name'); // 'name' column in Events table
    } elseif ($sort === 'date') {
    // This will work only if you sort after retrieving, or join manually
    // Better: sort in-memory after fetching related model
    $query->with(['eventVenue' => function ($q) {
    $q->orderBy('event_datetime');
    }]);
    }

    // Fetch events
    $events = $query->get();

    // Optional: search again by name using binary search
    if ($queryTerm) {
    if ($sort === 'name') {
    // Perform binary search or similar custom filter
    $filtered = $this->binarySearchEventsByTitle($events, $queryTerm);
    return response()->json($filtered);
    } else {
    // Fallback: simple in-memory filter
    $filtered = $events->filter(function ($event) use ($queryTerm) {
    return str_contains(strtolower($event->name), strtolower($queryTerm));
    });
    return response()->json($filtered->values());
    }
    }

    return response()->json($events);
 }
}
