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
        $maxEvents = 104; // for testing purpose now
        $categoryEventMap = [];



        // Spread 104 events across categories
        foreach ($categories as $category) {
            $categoryEventMap[$category->id] = rand(1, 6);
        }

        foreach ($locations as $location) {
            $locationVenues = $venues->where('location_id', $location->id)->take(rand(2, 4)); // select 2 to 4 venues per location

            foreach ($locationVenues as $venue) {
                $numEvents = rand(2, 4); // select 2 to 4 events per venue

                for ($i = 0; $i < $numEvents; $i++) {
                    if ($eventCount >= $maxEvents)
                        break 3; // break out of all 3 loops if 104 events details are already ready to create

                    $category = $categories->random(); // select a random category

                    // Skip if category is already full
                    if ($categoryEventMap[$category->id] <= 0) {
                        continue;
                    }

                    // creates all the random details for an event (testing purpose)
                    $title = Str::title(Str::random(10)) . " Live";
                    $description = "Exciting event happening at {$venue->venue_name} in {$location->city}.";
                    $duration = Carbon::createFromTime(rand(1, 3), rand(0, 59))->format('H:i:s');


                    // set thumbnail based on category (testing purpose)
                    if ($category->name == "Comedy") {
                        $thumbnail = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTtLLuFehdI59tZZ96puFS0UIzDQGOWu6EYfA&s";
                    } elseif ($category->name == "Kids") {
                        $thumbnail = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1PWoTB8O3kGuDqVQoy96-4cT4WdwdyawpZw&s";
                    } elseif ($category->name == "Music") {
                        $thumbnail = "https://img.freepik.com/free-psd/a4-rock-music-festival-poster-template_220664-3861.jpg?semt=ais_hybrid&w=740";
                    } elseif ($category->name == "Workshops") {
                        $thumbnail = "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/multipurpose-workshop-course-flyer-design-template-804e41ea60a49745ef060af050a9f326_screen.jpg?ts=1636993491";
                    } elseif ($category->name == "Performance") {
                        $thumbnail = "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/dance-class-flyer-template-90adacee440bf5418eeef5aa2b232007_screen.jpg?ts=1636966335";
                    } elseif ($category->name == "Sports") {
                        $thumbnail = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnfdV2p7OIUWZrh-ekHLrNE54YsVeKL6nN_g&s";
                    } else {
                        $thumbnail = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNdi6Gavxh_hhmb3SY4wDfn-mvdtPkvMvKKA&s";
                    }

                    $event = Event::create([
                        'title' => $title,
                        'description' => $description,
                        'thumbnail' => $thumbnail,
                        'duration' => $duration,
                        'category_id' => $category->id,
                        'admin_id' => $admin->id,
                    ]);

                    EventVenue::create([
                        'event_id' => $event->id,
                        'location_id' => $location->id,
                        'venue_id' => $venue->id,
                        'start_datetime' => Carbon::now()->addDays(rand(1, 60))->addHours(rand(1, 12)),
                    ]);

                    $categoryEventMap[$category->id]--;
                    $eventCount++;
                }
            }
        }

        echo "Seeded $eventCount events.\n";
    }
}
