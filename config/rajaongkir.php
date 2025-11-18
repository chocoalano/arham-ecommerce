<?php

return [

    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Key
    |--------------------------------------------------------------------------
    |
    | Your RajaOngkir API key. You can get this from:
    | https://rajaongkir.com/akun/panel
    |
    */

    'key_cost' => env('RAJAONGKIR_API_KEY_COST', ''),
    'key_shipping' => env('RAJAONGKIR_API_KEY_SHIPPING', ''),

    /*
    |--------------------------------------------------------------------------
    | RajaOngkir Account Type
    |--------------------------------------------------------------------------
    |
    | Your account type: starter, basic, or pro
    | Different account types have different features and API endpoints
    |
    | - Starter: Basic shipping cost calculation (JNE, POS, TIKI)
    | - Basic: More couriers available
    | - Pro: All features including subdistrict, waybill tracking, international
    |
    */

    'type' => env('RAJAONGKIR_TYPE', 'starter'),

    /*
    |--------------------------------------------------------------------------
    | Default Origin City
    |--------------------------------------------------------------------------
    |
    | Default origin city ID for shipping calculations
    | Example: 501 for Yogyakarta, 153 for Jakarta Pusat
    |
    */

    'origin_city' => env('RAJAONGKIR_ORIGIN_CITY', 153),

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    |
    | Duration in seconds to cache provinces, cities data
    | Default: 86400 (24 hours)
    |
    */

    'cache_duration' => env('RAJAONGKIR_CACHE_DURATION', 86400),

    /*
    |--------------------------------------------------------------------------
    | Default Couriers
    |--------------------------------------------------------------------------
    |
    | Default couriers to use when calculating shipping costs
    | Add couriers based on your account type
    |
    */

    'default_couriers' => [
        'jne',
        'pos',
        'tiki',
    ],

];
