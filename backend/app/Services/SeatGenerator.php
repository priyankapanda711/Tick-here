<?php

namespace App\Services;

use App\Models\Seat;

class SeatGenerator
{
    // it generates the seat label and price for each row
    public static function generateSeats(int $venueId, int $maxSeats): void
    {
        $seatsPerRow = $maxSeats > 1040 ? 25 : 20; //no. of seats per row
        $totalRows = ceil($maxSeats / $seatsPerRow); //no. of rows

        $seats = [];
        $seatCounter = 1;

        for ($i = 0; $i < $totalRows; $i++) { // this loop for each row
            $rowLabel = self::numberToAlphabet($i); //returns A or B or ... AA, AB ...
            $price = self::getPriceForRow($rowLabel); //returns A-D -> 1000, E-H -> 800 like...

            for ($j = 1; $j <= $seatsPerRow && $seatCounter <= $maxSeats; $j++, $seatCounter++) { //this loop for each column (seat)

                $seatNumber = str_pad((string) $j, 2, '0', STR_PAD_LEFT); // sets the seat no. like 01, 02, 03 ...

                $label = $rowLabel . $seatNumber; // sets full label

                $seats[] = [
                    'venue_id' => $venueId,
                    'row_label' => $rowLabel,
                    'seat_no' => $j,
                    'label' => $label,
                    'price' => $price,
                    'is_booked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seats);
    }

    // returns the alphabet acc. to the number whether single (A...Z) or combine (AA...ZZ)
    private static function numberToAlphabet(int $number): string
    {
        $alphabet = '';
        while ($number >= 0) {
            $alphabet = chr($number % 26 + 65) . $alphabet;
            $number = intval($number / 26) - 1;
        }
        return $alphabet;
    }

    private static function getPriceForRow(string $rowLabel): float
    {
        $premiumRows = ['A', 'B', 'C', 'D'];
        $goldRows = ['E', 'F', 'G', 'H'];
        $silverRows = ['I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];

        if (in_array($rowLabel, $premiumRows)) return 1000.00;
        if (in_array($rowLabel, $goldRows)) return 800.00;
        if (in_array($rowLabel, $silverRows)) return 600.00;

        return 400.00; // Default/Back rows
    }
}
