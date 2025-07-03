<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventVenue;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    //register an admin
    public function store()
    {
        try {
            $data = request()->validate([
                'name' => 'required | min:8',
                'phone' => ['digits:10', Rule::unique('admins', 'phone')],
                'email' => ['required', 'email', Rule::unique('admins', 'email')],
                'password' => 'required|min:8'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $data['username'] = substr($data['email'], 0, strpos($data['email'], '@'));

        $data['password'] = Hash::make($data['password']);

        $admin = Admin::create($data);

        return response()->json([
            'message' => 'Admin created successfully',
            'user' => $admin,
            'token' => $admin->createToken('admin-token')->plainTextToken
        ], 201);
    }

    //login an admin
    public function login()
    {
        try {
            $data = request()->validate([
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $admin = Admin::where('email', $data['email'])->first();

        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        }

        // Create a Sanctum token
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Authentication Successful',
            'token' => $token,
            'data' => $admin
        ]);
    }

    //For logging out an admin(who is also a user)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }

    //For getting current user profile
    public function profile(Request $request)
    {
        return response()->json([
            'admin' => $request->user()
        ], 200);
    }

    //For updating user profile
    public function update(Request $request)
    {
        try {
            $admin = $request->user();

            $data = request()->validate([
                'name' => 'required|min:8',
                'username' => ['required', 'min:8', Rule::unique('admins', 'username')->ignore($request->user()->id)],
                'phone' => ['required', 'digits:10', Rule::unique('admins', 'phone')->ignore($request->user()->id)],
                'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($request->user()->id)],
                'password' => 'required|min:8',
            ]);

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $admin->update($data);

            return response()->json(['message' => 'Profile updated']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function getEventStats(Request $request)
    {
        try {
            $admin = auth()->user();

            if (!$admin) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $today = Carbon::now();
            $startOfThisWeek = $today->copy()->startOfWeek();
            $endOfThisWeek = $today->copy()->endOfWeek();

            // Get all categories along with an array of events with a nested array of venues for each event
            $categories = EventCategory::with(['events.eventVenue'])->get();
            $result = [];

            foreach ($categories as $category) {
                $totalEvents = $category->events->count();

                // Filter events that contains any event venue scheduled this week
                $thisWeek = $category->events->filter(function ($event) use ($startOfThisWeek, $endOfThisWeek) {
                    return $event->eventVenue->contains(function ($venue) use ($startOfThisWeek, $endOfThisWeek) {
                        return Carbon::parse($venue->start_datetime)->between($startOfThisWeek, $endOfThisWeek);
                    });
                })->count();

                $growthPercentage = $totalEvents === 0 ? ($thisWeek === 0 ? 0 : 100) : round(($thisWeek / $totalEvents) * 100);

                $result[] = [
                    'category' => $category->name,
                    'total_events' => $totalEvents,
                    'this_week_events' => $thisWeek,
                    'growth_percentage' => $growthPercentage,

                ];
            }


            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Throwable $e) {
            \Log::error('Crash in getEventStats', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTicketStats()
    {
        // $startOfWeek = Carbon::now()->copy()->startOfWeek();
        // $endOfWeek = Carbon::now()->copy()->endOfWeek();

        $categories = EventCategory::all();
        $result = [];

        foreach ($categories as $category) {
            $ticketsSold = Ticket::whereHas('eventVenue.event', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            })->where('status', 'booked')->count();

            $result[] = [
                'category' => $category->name,
                'tickets_sold' => $ticketsSold
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    // Return all active admins
    public function activeAdmins()
    {
        $admins = User::where('role', 'admin')
                      ->where('is_active', true)
                      ->get();

        $data = $admins->map(function ($admin) {
            return [
                'name' => $admin->name,
                'avatar' => $admin->avatar ?? 'https://i.pravatar.cc/100?u=' . $admin->email,
            ];
        });

        return response()->json($data);
    }
}
