<?php

return [
    'heads' => [
        'MEDICAL' => [
            'name' => 'Medical Payment',
            'account_number' => 'ACC-MEDICAL-001',
            'prefix' => '10',
        ],
        'TRAFFIC_CAR' => [
            'name' => 'Car Traffic Challan',
            'account_number' => 'ACC-TRAFFIC-CAR-002',
            'prefix' => '20',
        ],
        'TRAFFIC_BIKE' => [
            'name' => 'Bike Traffic Challan',
            'account_number' => 'ACC-TRAFFIC-BIKE-003',
            'prefix' => '30',
        ],
    ],
    'sandbox' => [
        'enabled' => env('BANK_SANDBOX_ENABLED', true),
        'api_url' => env('BANK_API_URL', 'http://localhost:8000/api/mock-bank'),
    ],
];
