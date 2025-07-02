<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;


    public function eventVenue()
    {
        return $this->belongsTo(EventVenue::class);
    }

    public function event()
    {
        return $this->eventVenue->event;
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
