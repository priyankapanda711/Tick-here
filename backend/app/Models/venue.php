<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = ['vanue_name','location_id'];

    //this is used to establish the relationship between venue and event_venue table
    public function eventVenues(){
        return $this->hasMany(EventVenue::class);
    }
}
