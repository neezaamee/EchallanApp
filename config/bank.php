<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Heads (Bank Accounts)
    |--------------------------------------------------------------------------
    |
    | Maps each payment category to its bank account number and prefix used
    | for legacy mock generation. The category codes used inside PSIDs are
    | defined directly in BankService (1=Medical, 2=Car, 3=Bike, 4=Other).
    |
    */
    'heads' => [
        'MEDICAL' => [
            'name'           => 'Medical Payment',
            'account_number' => 'ACC-MEDICAL-001',
            'prefix'         => '10',
        ],
        'TRAFFIC_CAR' => [
            'name'           => 'Car Traffic Challan',
            'account_number' => 'ACC-TRAFFIC-CAR-002',
            'prefix'         => '20',
        ],
        'TRAFFIC_BIKE' => [
            'name'           => 'Bike Traffic Challan',
            'account_number' => 'ACC-TRAFFIC-BIKE-003',
            'prefix'         => '30',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PSID Version
    |--------------------------------------------------------------------------
    |
    | Controls which PSID format BankService generates:
    |
    |   20 → Legacy V1: [Category(1)][CityCode(3)][ymd(6)][Random(9)][Luhn(1)]
    |   12 → New V2:    [Category(1)][yy(2)][ddd(3)][Random(5)][Luhn(1)]
    |
    | Switching between versions requires only changing this .env variable —
    | no code deployment is needed. Old PSIDs of either length remain fully
    | resolvable in the database regardless of which version is active.
    |
    | Rollout guide:
    |   Phase 1 (now):     PSID_VERSION=20  — safe default, no behaviour change
    |   Phase 2 (staging): PSID_VERSION=12  — test against 1Link sandbox
    |   Phase 3 (live):    PSID_VERSION=12  — flip after bank sign-off
    |   Rollback:          PSID_VERSION=20  — instant, no code change needed
    |
    | FUTURE SUGGESTION: After full migration to 12-digit PSIDs is stable,
    | consider archiving or expiring very old unpaid 20-digit PSIDs as a
    | housekeeping task. Not urgent — system handles both lengths indefinitely.
    |
    */
    'psid_version' => (int) env('PSID_VERSION', 20),

    /*
    |--------------------------------------------------------------------------
    | Sandbox / Mock Bank Settings
    |--------------------------------------------------------------------------
    */
    'sandbox' => [
        'enabled'        => env('BANK_SANDBOX_ENABLED', true),
        'api_url'        => env('BANK_API_URL', 'http://localhost:8000/api/mock-bank'),
        'callback_token' => env('BANK_CALLBACK_TOKEN', 'dummy_token_123'),
    ],

];
