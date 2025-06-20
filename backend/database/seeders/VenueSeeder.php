<?php

namespace Database\Seeders;

use App\Models\Location;
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
            $location = Location::where('location_name', $locationName)->first();

            if ($location) {
                foreach ($venueNames as $venue) {
                    DB::table('venues')->insert([
                        'venue_name' => $venue,
                        'location_id' => $location->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
