<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
    protected $with = ['eventVenue', 'category', 'admin'];

    public function eventVenue()
    {
        return $this->hasMany(EventVenue::class);
    }
    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id', );
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // calculate the status of the event
    public function scopeWithComputedStatus(Builder $query, string $status)
    {
        $status = strtolower($status); // normalize case
        $now = Carbon::now();
        $oneMonthLater = Carbon::now()->addMonth();

        if ($status === 'cancelled') {
            return $query->doesntHave('eventVenue');
        }

        return $query->whereHas('eventVenue', function ($q) use ($status, $now, $oneMonthLater) {
            if ($status === 'completed') {
                $q->where('start_datetime', '<', $now);
            } elseif ($status === 'active') {
                $q->whereBetween('start_datetime', [$now, $oneMonthLater]);
            } elseif ($status === 'inactive') {
                $q->where('start_datetime', '>', $oneMonthLater);
            }
        });
    }

}
