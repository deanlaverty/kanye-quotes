<?php

declare(strict_types=1);

namespace App\Services\Quotes;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

readonly class KanyeQuotesDriver implements QuotesDriver
{
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
        $quotesResponse = Http::pool(fn (Pool $pool) => [
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
        ]);

        $quotes = array_map(
            fn (Response $response) => $response->json('quote'),
            $quotesResponse
        );

        return collect($quotes);
    }
}
