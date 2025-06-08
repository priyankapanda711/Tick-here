<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

// /**
//  * @group Authentication
//  * */
// //user registration
// Route::post('/users', [UserController::class, 'store']);
// //user login
// Route::post('/auth/user/login', [UserController::class, 'login']);

// //admin registration
// Route::post('/admin', [AdminController::class, 'store']);
// //admin login
// Route::post('/auth/admin/login', [AdminController::class, 'login']);

// //the middleware that only authenticated users can access
// Route::middleware('auth:sanctum')->group(function(){
//     //user logout
//     Route::post('/auth/user/logout', [UserController::class, 'logout']);
//     //get current user profile
//     Route::get('/auth/user/profile', [UserController::class, 'profile']);
//     //update user profile
//     Route::put('/auth/user/profile', [UserController::class, 'update']);
// });

// Route::middleware('auth:sanctum')->group(function () {
//     //admin logout
//     Route::post('/auth/admin/logout', [AdminController::class, 'logout']);
//     //get current admin profile
//     Route::get('/auth/admin/profile', [AdminController::class, 'profile']);
//     //update admin profile
//     Route::put('/auth/admin/profile', [AdminController::class, 'update']);

//     /**
//     * @group Events
//     */
//     //creates an event
//     Route::post('/create-event', [EventController::class, 'store']);
//     //delete an event
//     Route::delete('/events/{event}', [EventController::class, 'delete']);

//     /**
//     * @group Locations
//     */
//     //creates a location
//     Route::post('/admin/locations', [LocationController::class, 'create']);
//     //get all the locations
//     Route::get('/admin/locations', [LocationController::class, 'index']);

//     /**
//     * @group Venues
//     */
//     //creates a venue
//     Route::post('/admin/venues', [VenueController::class, 'index']);
//     //get all the venues
//     Route::post('/admin/venues', [VenueController::class, 'create']);

// });

// //public routes

// //get all the events
// Route::post('/', [EventController::class, 'index']);

// //get an event
// Route::get('/events/{eventId}', [EventController::class, 'getEvent']);

// //get all the venues for a specific event
// Route::get('/events/{eventId}/venues',[EventVenuesController::class,'getVenuesByEvent']);
