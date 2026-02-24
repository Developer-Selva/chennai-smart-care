<?php

return [

    'max_per_slot'  => env('BOOKING_MAX_PER_SLOT', 3),
    'advance_days'  => env('BOOKING_ADVANCE_DAYS', 30),

    'start_hour'    => 9,
    'end_hour'      => 21,
    'slot_duration' => 2,

    'city_center' => [
        'lat' => env('CHENNAI_LAT', 13.0827),
        'lng' => env('CHENNAI_LNG', 80.2707),
    ],

    'radius_km' => env('BOOKING_RADIUS_KM', 20),
];