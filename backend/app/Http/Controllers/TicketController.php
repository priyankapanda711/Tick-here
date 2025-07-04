<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventVenue;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\TicketSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class TicketController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'eventID' => 'required|integer|exists:event_venues,event_id',
                'venue_id' => 'required|integer|exists:event_venues,venue_id',
                'id' => 'required|integer|exists:users,id',
                'price' => 'required|numeric|min:0',
            ]);

            // Find the matching EventVenue
            $eventVenue = EventVenue::where('event_id', $validated['eventID'])
                ->where('venue_id', $validated['venue_id'])
                ->first();

            if (! $eventVenue) {
                return response()->json(['success' => false, 'message' => 'Event venue not found'], 404);
            }

            // Generate unique ticket code
            $ticketCode = 'EVENTIX-' . Str::upper(Str::random(10));


            // Create the ticket
            $ticket = Ticket::create([
                'user_id' => $validated['id'],
                'event_venue_id' => $eventVenue->id,
                'ticket_code' => $ticketCode,
                'total_price' => $validated['price'],
            ]);

            $venueid = $request->input('venue_id');

            $booked_seats = $request->input('seats');

            if (!is_array($booked_seats)) {
                $booked_seats = json_decode($booked_seats, true);
            }

            foreach ($booked_seats as $seat) {
                $seat = Seat::where('venue_id', $venueid)->where('label', $seat)->first();
                if ($seat) {
                    TicketSeat::create([
                        "ticket_id" => $ticket->id,
                        "seat_id" => $seat->id
                    ]);
                } else {
                    Log::warning("Seat label not found: " . $seat);
                }
            }
            // Return success response
            return response()->json([
                'success' => true,
                'ticket' => $ticket,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTicketsByUser()
    {
        $userId = request('userid');

        $tickets = Ticket::where('user_id', $userId)->get();

        if ($tickets->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tickets found for this user.',
            ], 404);
        }

        $ticketData = [];

        foreach ($tickets as $ticket) {
            $seat_ids = TicketSeat::where('ticket_id', $ticket->id)->pluck('seat_id');
            $seats = Seat::whereIn('id', $seat_ids)->pluck('label');
            $ticketData[] = [
                'ticket_id' => $ticket->id,
                'seats' => $seats,
                'price' => $ticket->total_price,
                'status' => $ticket->status,
                'event_venue_details' => $ticket->eventVenue
            ];
        }

        return response()->json([
            'success' => true,
            'tickets' => $ticketData,
        ]);
    }

    public function getEventByTicketId(Request $request)
    {
        $ticketId = $request->input('ticket_id');

        $event_venue_id = Ticket::where("id", $ticketId)->value('event_venue_id');

        $event_venue_details = EventVenue::find($event_venue_id);

        if (! $event_venue_details) {
            return response()->json([
                'success' => false,
                'message' => 'Event venue not found',
            ], 404);
        }

        $event = Event::find($event_venue_details->event_id);

        return response()->json([
            "success" => true,
            "event_venue_details" => $event_venue_details,
            "event" => $event
        ], 200);
    }
    public function cancelticket(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $ticket = Ticket::with('ticketSeats.seat')->find($request->ticket_id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Ticket already cancelled.',
            ], 400);
        }

        // Step 1: Cancel the ticket
        $ticket->status = 'cancelled';
        $ticket->save();

        // Step 2: Release all booked seats
        foreach ($ticket->ticketSeats as $ticketSeat) {
            $seat = $ticketSeat->seat;
            if ($seat) {
                $seat->is_booked = 0;
                $seat->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket cancelled and seats released.',
        ]);
    }
     public function getTicketsByUser($userId)
    {
        $tickets = Ticket::with([
            'eventVenue.event',        // Event details (title, description)
            'eventVenue.location',     // Location details (location_name, state)
            'ticketSeats.seat'         // Each booked seat
        ])->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($tickets->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tickets found for this user.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'tickets' => $tickets,
        ]);
    }

    public function bookTicket(Request $request)
    {
    $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_venue_id' => 'required|exists:event_venues,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        // Check if the seat belongs to the given event venue
        $seat = Seat::where('id', $validated['seat_id'])
                    ->where('event_venue_id', $validated['event_venue_id'])
                    ->first();

        if (!$seat) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid seat or event venue selected.',
            ], 404);
        }

        if ($seat->is_booked) {
            return response()->json([
                'success' => false,
                'message' => 'This seat is already booked.',
            ], 409);
        }

        DB::beginTransaction();
        try {
            // Create a new ticket
            $ticket = Ticket::create([
                'user_id' => $validated['user_id'],
                'event_venue_id' => $validated['event_venue_id'],
                'ticket_code' => strtoupper(Str::random(8)),
                'status' => 'booked',
            ]);

            // Link the seat with the ticket in pivot table
            TicketSeat::create([
                'ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
            ]);

            // Mark the seat as booked
            $seat->update(['is_booked' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ticket booked successfully.',
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to book ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancelTicket(Request $request)
    {
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Step 1: Get latest booked ticket for user (LIFO logic)
        $ticket = Ticket::where('user_id', $validated['user_id'])
                        ->where('status', 'booked')
                        ->orderBy('id', 'desc') // latest = stack top
                        ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'No active tickets found to cancel.',
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Step 2: Get seat(s) linked to this ticket
            $ticketSeats = TicketSeat::where('ticket_id', $ticket->id)->get();

            foreach ($ticketSeats as $ticketSeat) {
                $seat = Seat::find($ticketSeat->seat_id);
                if ($seat) {
                    $seat->is_booked = false;
                    $seat->save();
                }
            }

            // Step 3: Update ticket status to cancelled
            $ticket->status = 'cancelled';
            $ticket->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Most recent ticket cancelled successfully.',
                'ticket_id' => $ticket->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ticket cancellation failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getBookedTickets($eventId)
    {
        try {
            $bookedTickets = Ticket::with(['user', 'seats', 'eventVenue.event'])
                ->where('status', 'booked')
                ->whereHas('eventVenue', function ($query) use ($eventId) {
                    $query->where('event_id', $eventId);
                })
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bookedTickets,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching booked tickets: ' . $e->getMessage(),
            ], 500);
        }
    }
}
