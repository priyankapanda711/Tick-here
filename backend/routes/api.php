<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventVenuesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * PUBLIC ROUTES
 * Accessible without authentication
 */

// User registration & login
Route::post('/users', [UserController::class, 'store']);
Route::post('/auth/user/login', [UserController::class, 'login']);

// Admin registration & login
Route::post('/admin', [AdminController::class, 'store']);
Route::post('/auth/admin/login', [AdminController::class, 'login']);

// Public Event Data
Route::post('/', [EventController::class, 'index']); // Get all events
Route::get('/events/{eventId}', [EventController::class, 'getEvent']); // Get event details
Route::get('/events/{eventId}/venues', [EventVenuesController::class, 'getVenuesByEvent']); // Venues for an event

/**
 * PROTECTED ROUTES
 * Require authentication using Sanctum
 */
Route::middleware('auth:sanctum')->group(function () {

    /**
     * USER ROUTES
     */
    Route::post('/auth/user/logout', [UserController::class, 'logout']);
    Route::get('/auth/user/profile', [UserController::class, 'profile']);
    Route::put('/auth/user/profile', [UserController::class, 'update']);

    /**
     * ADMIN ROUTES
     */
    Route::post('/auth/admin/logout', [AdminController::class, 'logout']);
    Route::get('/auth/admin/profile', [AdminController::class, 'profile']);
    Route::put('/auth/admin/profile', [AdminController::class, 'update']);

    // Events
    Route::post('/create-event', [EventController::class, 'store']);
    Route::delete('/events/{event}', [EventController::class, 'delete']);

    // Locations
    Route::post('/admin/locations', [LocationController::class, 'create']);
    Route::get('/admin/locations', [LocationController::class, 'index']);

    // Venues
    Route::post('/admin/venues', [VenueController::class, 'create']); // FIXED: 'create' should be for POST
    Route::get('/admin/venues', [VenueController::class, 'index']);   // FIXED: 'index' should be for GET
});

