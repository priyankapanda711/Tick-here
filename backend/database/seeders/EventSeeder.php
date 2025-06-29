<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventVenue;
use App\Models\Location;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $admin = Admin::first(); // the first one admin
        $categories = EventCategory::all();
        $locations = Location::all();
        $venues = Venue::all();

        $eventCount = 0;
        $maxEvents = 416; // for testing purpose now
        $categoryEventMap = [];

        // Distribute 416 events across categories
        foreach ($categories as $category) {
            $categoryEventMap[$category->id] = rand(40, 70);
        }

        foreach ($locations as $location) {
            $locationVenues = $venues->where('location_id', $location->id)->take(rand(2, 4)); // select 2 to 4 venues per location

            foreach ($locationVenues as $venue) {
                $numEvents = rand(5, 10); // allow more events per venue

                for ($i = 0; $i < $numEvents; $i++) {
                    if ($eventCount >= $maxEvents)
                        break 3; // break out of all 3 loops if 416 events details are already ready to create

                    $category = $categories->random(); // select a random category

                    // Skip if category is already full
                    if ($categoryEventMap[$category->id] <= 0)
                        continue; // creates all the random details for an event (testing purpose)

                    $title = Str::title(Str::random(10)) . " Live";
                    $description = "Exciting event happening at {$venue->venue_name} in {$location->city}.";
                    $duration = Carbon::createFromTime(rand(1, 3), rand(0, 59))->format('H:i:s');

                    // Thumbnail assignment like controller logic
                    $thumbnail = match ($category->name) {
                        'Comedy' => 'comedy.jpg',
                        'Kids' => 'kids.jpg',
                        'Music' => 'music.jpg',
                        'Workshops' => 'workshops.jpg',
                        'Performance' => 'performance.jpg',
                        'Sports' => 'sports.jpg',
                        default => 'default.jpg',
                    };

                    $event = Event::create([
                        'title' => $title,
                        'description' => $description,
                        'thumbnail' => $thumbnail, // static file path, must exist in storage/app/public/thumbnails
                        'duration' => $duration,
                        'category_id' => $category->id,
                        'admin_id' => $admin->id,
                    ]);

                    // 🕑 Add 1–3 slots across 1–2 days
                    $dayOffset = rand(1, 30);
                    $baseDate = Carbon::now()->addDays($dayOffset);

                    $slotsPerEvent = rand(1, 3);
                    for ($slot = 0; $slot < $slotsPerEvent; $slot++) {
                        $startTime = (clone $baseDate)->addHours(rand(10, 20));
                        EventVenue::create([
                            'event_id' => $event->id,
                            'location_id' => $location->id,
                            'venue_id' => $venue->id,
                            'start_datetime' => $startTime,
                        ]);
                    }

                    $categoryEventMap[$category->id]--;
                    $eventCount++;
                }
            }
        }

        echo "Seeded $eventCount events.\n";
    }


}
