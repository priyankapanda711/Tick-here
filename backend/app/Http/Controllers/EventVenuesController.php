<?php

namespace App\Http\Controllers;

use App\Models\EventVenue;
use App\Models\Ticket;
use Illuminate\Http\Request;

class EventVenuesController extends Controller
{
    //get all the venues for an event and give the structured data in json format as response
    public function getVenuesByEvent($eventId)
    {
        $venues = EventVenue::with(['location', 'venue', 'tickets'])
            ->where('event_id', $eventId)
            ->get()
            ->map(function ($ev) {
                return [
                    'id' => $ev->id,
                    'venue_id' => $ev->venue->id,
                    'venue_name' => $ev->venue->venue_name,
                    'location_name' => $ev->location->city,
                    'state' => $ev->location->state,
                    'country' => $ev->location->country,
                    'start_datetime' => $ev->start_datetime,
                    'total_seats' => $ev->venue->max_seats,
                    'tickets_booked' => $ev->tickets->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $venues
        ]);
    }


    //get all the tickets for an event held in a venue(admin only)
    public function getTicketsByEventVenue($eventVenueId)
    {
        // Fetch all tickets for the given event_venue_id
        $tickets = Ticket::with('user', 'seats') // assuming a relationship exists
            ->where('event_venue_id', $eventVenueId)
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'total_price' => $ticket->total_price,
                    'status' => $ticket->status,
                    'user_email' => $ticket->user->email ?? 'N/A',
                    'user_phone' => $ticket->user->phone ?? 'N/A',
                    'seats_booked' => $ticket->seats->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $tickets
        ]);
    }

}
