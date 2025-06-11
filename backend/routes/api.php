<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventCategoryController;
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
Route::get('/events/{event}', [EventController::class, 'getEvent']); // Get event details
Route::get('/events/{event}/venues', [EventVenuesController::class, 'getVenuesByEvent']); // Venues for an event

/**
 * PROTECTED ROUTES
 * Require authentication using Sanctum
 */

/**
 * USER ROUTES
000 */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/user/logout', [UserController::class, 'logout']);
    Route::get('/auth/user/profile', [UserController::class, 'profile']);
    Route::put('/auth/user/profile', [UserController::class, 'update']);
});

/**
 * ADMIN ROUTES
 */
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/auth/admin/logout', [AdminController::class, 'logout']);
    Route::get('/auth/admin/profile', [AdminController::class, 'profile']);
    Route::put('/auth/admin/profile', [AdminController::class, 'update']);

    // Events
    Route::post('/', [EventController::class, 'index']); // Get all events
    Route::post('/create-event', [EventController::class, 'create']);
    Route::delete('/events/{event}', [EventController::class, 'delete']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::get('/events/{eventId}/tickets', [EventVenuesController::class, 'getTicketsByEvent']);

    // Locations
    Route::post('/admin/locations', [LocationController::class, 'create']);
    Route::get('/admin/locations', [LocationController::class, 'index']);
    Route::delete('/admin/locations/{location}', [LocationController::class, 'delete']);
    Route::put('/admin/locations/{location}', [LocationController::class, 'update']);

    // Venues
    Route::post('/admin/venues', [VenueController::class, 'create']);
    Route::get('/admin/venues', [VenueController::class, 'index']);
    Route::delete('/admin/venues/{venue}', [VenueController::class, 'delete']);
    Route::put('/admin/venues/{venue}', [VenueController::class, 'update']);

    //Categories
    Route::post('/admin/categories', [EventCategoryController::class, 'index']);
    Route::post('/admin/categories', [EventCategoryController::class, 'create']);
    Route::delete('/admin/categories/{category}', [EventCategoryController::class, 'delete']);
    Route::put('/admin/categories/{category}', [EventCategoryController::class, 'update']);
});

