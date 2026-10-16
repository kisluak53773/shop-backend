<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default currency
    |--------------------------------------------------------------------------
    |
    | Applied when a model or resource needs a currency but doesn't have
    | one available on the row (e.g. an empty cart's subtotal).
    |
    | Must be a valid ISO 4217 currency code.
    |
    */
    'currency' => env('SHOP_CURRENCY', 'USD'),
];
