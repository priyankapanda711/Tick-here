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
                'location_name' => $location->location_name,
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
    public function getTicketsByEvent($eventId){

    }
}
