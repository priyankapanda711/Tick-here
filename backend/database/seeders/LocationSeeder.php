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
            ['country' => 'India', 'state' => 'Odisha', 'location_name' => 'Bhubaneswar'],
            ['country' => 'India', 'state' => 'Delhi', 'location_name' => 'New Delhi'],
            ['country' => 'India', 'state' => 'Maharashtra', 'location_name' => 'Mumbai'],
            ['country' => 'India', 'state' => 'Karnataka', 'location_name' => 'Bangalore'],
            ['country' => 'India', 'state' => 'Tamil Nadu', 'location_name' => 'Chennai'],

            // USA
            ['country' => 'USA', 'state' => 'California', 'location_name' => 'Los Angeles'],
            ['country' => 'USA', 'state' => 'New York', 'location_name' => 'New York City'],
            ['country' => 'USA', 'state' => 'Illinois', 'location_name' => 'Chicago'],
            ['country' => 'USA', 'state' => 'Texas', 'location_name' => 'Houston'],
            ['country' => 'USA', 'state' => 'Nevada', 'location_name' => 'Las Vegas'],

            // UK
            ['country' => 'United Kingdom', 'state' => 'England', 'location_name' => 'London'],
            ['country' => 'United Kingdom', 'state' => 'Scotland', 'location_name' => 'Edinburgh'],
            ['country' => 'United Kingdom', 'state' => 'Wales', 'location_name' => 'Cardiff'],
            ['country' => 'United Kingdom', 'state' => 'Northern Ireland', 'location_name' => 'Belfast'],

            // Canada
            ['country' => 'Canada', 'state' => 'Ontario', 'location_name' => 'Toronto'],
            ['country' => 'Canada', 'state' => 'British Columbia', 'location_name' => 'Vancouver'],
            ['country' => 'Canada', 'state' => 'Quebec', 'location_name' => 'Montreal'],
            ['country' => 'Canada', 'state' => 'Alberta', 'location_name' => 'Calgary'],

            // Australia
            ['country' => 'Australia', 'state' => 'New South Wales', 'location_name' => 'Sydney'],
            ['country' => 'Australia', 'state' => 'Victoria', 'location_name' => 'Melbourne'],
            ['country' => 'Australia', 'state' => 'Queensland', 'location_name' => 'Brisbane'],
            ['country' => 'Australia', 'state' => 'Western Australia', 'location_name' => 'Perth'],

            // UAE
            ['country' => 'UAE', 'state' => 'Dubai', 'location_name' => 'Dubai'],
            ['country' => 'UAE', 'state' => 'Abu Dhabi', 'location_name' => 'Abu Dhabi'],

            // Japan
            ['country' => 'Japan', 'state' => 'Tokyo', 'location_name' => 'Tokyo'],
            ['country' => 'Japan', 'state' => 'Osaka', 'location_name' => 'Osaka'],
        ];


        foreach ($locations as $location) {
            DB::table('locations')->insert([
                'country' => $location['country'],
                'state' => $location['state'],
                'location_name' => $location['location_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
