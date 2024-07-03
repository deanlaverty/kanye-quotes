<?php

declare(strict_types=1);

return [
    'driver' => 'kanye',
    'api_url' => env('QUOTES_API_URL', 'https://api.kanye.rest'),
    'cache_ttl' => env('QUOTES_CACHE_TTL', 120),
];
