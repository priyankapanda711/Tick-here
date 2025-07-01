<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSeat extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'seat_id'];

    protected $with = ['seat'];

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
