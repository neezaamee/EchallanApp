<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Traffic Challan Fees
    |--------------------------------------------------------------------------
    |
    | Define the standard fine amounts for different vehicle types and 
    | violation categories.
    |
    */
    'traffic' => [
        'bike' => 200,
        'car'  => 2000,
        'other' => 3000, // Default for 'other' vehicle types
    ],

    /*
    |--------------------------------------------------------------------------
    | Medical Request Fees
    |--------------------------------------------------------------------------
    |
    | The fixed processing fee for medical requests.
    |
    */
    'medical' => 200,
];
