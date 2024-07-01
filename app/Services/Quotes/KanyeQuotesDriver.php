<?php

declare(strict_types=1);

namespace App\Services\Quotes;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

readonly class KanyeQuotesDriver implements QuotesDriver
{
    private const CACHE_VALUE = 'kanye-quotes';

    /**
     * @param string $apiUrl
     */
    public function __construct(private string $apiUrl)
    {
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        $quotes = Cache::get(self::CACHE_VALUE, function () {
            $quotesResponse = Http::pool(fn (Pool $pool) => [
                $pool->get($this->apiUrl),
                $pool->get($this->apiUrl),
                $pool->get($this->apiUrl),
                $pool->get($this->apiUrl),
                $pool->get($this->apiUrl),
            ]);

            return array_map(
                fn (Response $response) => $response->json('quote'),
                $quotesResponse
            );
        });

        return collect($quotes);
    }
}
