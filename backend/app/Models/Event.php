<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'thumbnail', 'duration', 'admin_id', 'category_id'];

    //get all the different vanues for an event , where the event will going to be held
    public function eventVenues()
    {
        return $this->hasMany(EventVenue::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }
}


