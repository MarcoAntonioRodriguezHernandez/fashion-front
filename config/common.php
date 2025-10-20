<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Customs variables
    |--------------------------------------------------------------------------
    |
    */
    'pagination_limit' => env('PAGINATION_LIMIT', 5),

    /*
    |--------------------------------------------------------------------------
    duration in months of the client_credit model
    |--------------------------------------------------------------------------
    */
    'client_credit_validity' => env('CONSPIRACY_CLIENT_CREDIT_VALIDITY', 6),

    /*
    |--------------------------------------------------------------------------
    | Conspiracy stores
    |--------------------------------------------------------------------------
    */
    'conspiracy_marketplaces' => explode(',', env('CONSPIRACY_MARKETPLACES', '')),
];
