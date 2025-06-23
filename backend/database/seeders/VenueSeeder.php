<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Venue;
use App\Services\SeatGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $venues = [
            'Bhubaneswar' => ['Kalinga Stadium', 'Janata Maidan'],
            'New Delhi' => ['Jawaharlal Nehru Stadium', 'Pragati Maidan'],
            'Mumbai' => ['Wankhede Stadium', 'Jio World Centre'],
            'Bangalore' => ['Chinnaswamy Stadium', 'Bangalore Palace Grounds'],
            'Chennai' => ['Chennai Trade Centre', 'YMCA Nandanam'],

            'Los Angeles' => ['Staples Center', 'Hollywood Bowl'],
            'New York City' => ['Madison Square Garden', 'Central Park Arena'],
            'Chicago' => ['United Center', 'Grant Park Pavilion'],
            'Houston' => ['NRG Stadium', 'Toyota Center'],
            'Las Vegas' => ['T-Mobile Arena', 'Caesars Palace'],

            'London' => ['Wembley Stadium', 'The O2 Arena'],
            'Edinburgh' => ['Edinburgh Playhouse', 'Usher Hall'],
            'Cardiff' => ['Cardiff Castle Grounds', 'Motorpoint Arena'],
            'Belfast' => ['SSE Arena Belfast', 'Waterfront Hall'],

            'Toronto' => ['Scotiabank Arena', 'Rogers Centre'],
            'Vancouver' => ['BC Place', 'Queen Elizabeth Theatre'],
            'Montreal' => ['Bell Centre', 'Olympic Stadium'],
            'Calgary' => ['Scotiabank Saddledome', 'BMO Centre'],

            'Sydney' => ['Sydney Opera House', 'Qudos Bank Arena'],
            'Melbourne' => ['Melbourne Park', 'Rod Laver Arena'],
            'Brisbane' => ['Suncorp Stadium', 'Brisbane Convention Centre'],
            'Perth' => ['Optus Stadium', 'Perth Arena'],

            'Dubai' => ['Coca-Cola Arena', 'Dubai World Trade Centre'],
            'Abu Dhabi' => ['Etihad Arena', 'Zayed Sports City'],

            'Tokyo' => ['Tokyo Dome', 'Nippon Budokan'],
            'Osaka' => ['Osaka Dome', 'Osaka-jo Hall'],
        ];

        foreach ($venues as $locationName => $venueNames) {
            $location = Location::where('city', $locationName)->first();

            if ($location) {
                foreach ($venueNames as $venueName) {
                    $maxSeats = rand(500, 2000);  // randomly pics a value for max_seats

                    $venue = Venue::create([
                        'venue_name' => $venueName,
                        'location_id' => $location->id,
                        'max_seats' => $maxSeats,
                    ]);

                    // Generate seats for this venue
                    SeatGenerator::generateSeats($venue->id, $maxSeats);
                }
            }
        }
    }
}
