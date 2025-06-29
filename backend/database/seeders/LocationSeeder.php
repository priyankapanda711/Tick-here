<?php

namespace Database\Seeders;



use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            // India
            ['country' => 'India', 'state' => 'Odisha', 'city' => 'Bhubaneswar'],
            ['country' => 'India', 'state' => 'Delhi', 'city' => 'New Delhi'],
            ['country' => 'India', 'state' => 'Maharashtra', 'city' => 'Mumbai'],
            ['country' => 'India', 'state' => 'Karnataka', 'city' => 'Bangalore'],
            ['country' => 'India', 'state' => 'Tamil Nadu', 'city' => 'Chennai'],

            // USA
            ['country' => 'USA', 'state' => 'California', 'city' => 'Los Angeles'],
            ['country' => 'USA', 'state' => 'New York', 'city' => 'New York City'],
            ['country' => 'USA', 'state' => 'Illinois', 'city' => 'Chicago'],
            ['country' => 'USA', 'state' => 'Texas', 'city' => 'Houston'],
            ['country' => 'USA', 'state' => 'Nevada', 'city' => 'Las Vegas'],

            // UK
            ['country' => 'United Kingdom', 'state' => 'England', 'city' => 'London'],
            ['country' => 'United Kingdom', 'state' => 'Scotland', 'city' => 'Edinburgh'],
            ['country' => 'United Kingdom', 'state' => 'Wales', 'city' => 'Cardiff'],
            ['country' => 'United Kingdom', 'state' => 'Northern Ireland', 'city' => 'Belfast'],

            // Canada
            ['country' => 'Canada', 'state' => 'Ontario', 'city' => 'Toronto'],
            ['country' => 'Canada', 'state' => 'British Columbia', 'city' => 'Vancouver'],
            ['country' => 'Canada', 'state' => 'Quebec', 'city' => 'Montreal'],
            ['country' => 'Canada', 'state' => 'Alberta', 'city' => 'Calgary'],

            // Australia
            ['country' => 'Australia', 'state' => 'New South Wales', 'city' => 'Sydney'],
            ['country' => 'Australia', 'state' => 'Victoria', 'city' => 'Melbourne'],
            ['country' => 'Australia', 'state' => 'Queensland', 'city' => 'Brisbane'],
            ['country' => 'Australia', 'state' => 'Western Australia', 'city' => 'Perth'],

            // UAE
            ['country' => 'UAE', 'state' => 'Dubai', 'city' => 'Dubai'],
            ['country' => 'UAE', 'state' => 'Abu Dhabi', 'city' => 'Abu Dhabi'],

            // Japan
            ['country' => 'Japan', 'state' => 'Tokyo', 'city' => 'Tokyo'],
            ['country' => 'Japan', 'state' => 'Osaka', 'city' => 'Osaka'],
        ];


        foreach ($locations as $location) {
            DB::table('locations')->insert([
                'country' => $location['country'],
                'state' => $location['state'],
                'city' => $location['city'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
