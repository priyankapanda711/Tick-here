<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'duration',
        'admin_id',
        'category_id'
    ];

    // Automatically load eventVenue and its nested venue & location
    protected $with = ['eventVenue', 'category'];

    public function eventVenue()
    {
        return $this->hasMany(EventVenue::class);
    }
    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id', );
    }
}
