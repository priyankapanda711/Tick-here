<?php

namespace App\Http\Controllers;

use App\Models\EventVenue;
use Illuminate\Http\Request;

class EventVenuesController extends Controller
{
    //get all the venues for an event and give the structured data in json format as response
    public function getVenuesByEvent($eventId)
    {
        $venues = EventVenue::with(['venue', 'location'])
            ->where('event_id', $eventId)
            ->get()
            ->map(function ($ev) {
                return [
                    'venue_id' => $ev->venue->id,
                    'venue_name' => $ev->venue->venue_name,
                    'location' => [
                        'location_id' => $ev->location->id,
                        'location_name' => $ev->location->location_name,
                        'state' => $ev->location->state,
                        'country' => $ev->location->country,
                    ],
                    'available_seats' => $ev->available_seats,
                    'start_datetime' => $ev->start_datetime,
                    'end_datetime' => $ev->end_datetime,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $venues
        ]);
    }

    //get all the tickets for an event (admin only)
    public function getTicketsByEvent($eventId){

    }
}
