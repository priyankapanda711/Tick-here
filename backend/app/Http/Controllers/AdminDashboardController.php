<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function getEventStats()
    {
        $today = Carbon::now();
        $startOfThisWeek = $today->startOfWeek();
        $startOfLastWeek = (clone $startOfThisWeek)->subWeek();
        $endOfLastWeek = (clone $startOfThisWeek)->subSecond();

        $categories = EventCategory::all();
        $result = [];

        foreach ($categories as $category) {
            $totalEvents = Event::where('category_id', $category->id)->count();
            $eventsLastWeek = Event::where('category_id', $category->id)
                ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
                ->count();

            $growthPercentage = $eventsLastWeek == 0 ? 100 :
                (($totalEvents - $eventsLastWeek) / $eventsLastWeek) * 100;


            $result[] = [
                'category' => $category->name,
                'total_events' => $totalEvents,
                'last_week_events' => $eventsLastWeek,
                'growth_percentage' => round($growthPercentage),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getTicketStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $categories = EventCategory::all();
        $result = [];

        foreach ($categories as $category) {
            $ticketsSold = Ticket::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->whereHas('eventVenue.event', function ($q) use ($category) {
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
}

