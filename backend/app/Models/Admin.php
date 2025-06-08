<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    //these fields can be mass assignable
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
    ];



    //A guard defines how users are authenticated for each request. this saying that ,This model should be authenticated using the admin guard configuration."
    protected $guard = 'admin';

    //get all the events created by an admin
    public function Event()
    {
        return $this->hasMany(Event::class);
    }
}
