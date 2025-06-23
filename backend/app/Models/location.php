<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'state',
        'country'
    ];

    //this is used to establish the relationship between location and event_venue table
    public function eventVenues()
    {
        return $this->hasMany(EventVenue::class);
    }
}
