<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_venue_id', 'ticket_code', 'total_price'];

    protected $with = ['eventVenue', 'ticketSeats'];

    public function eventVenue()
    {
        return $this->belongsTo(EventVenue::class, 'event_venue_id');
    }

    public function ticketSeats()
    {
        return $this->hasMany(TicketSeat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seats()
    {
        return $this->hasMany(TicketSeat::class);
    }
}
