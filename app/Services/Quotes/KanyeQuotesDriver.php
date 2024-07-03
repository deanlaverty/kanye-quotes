<?php

declare(strict_types=1);

namespace App\Services\Quotes;

use App\Services\Quotes\Exceptions\KanyeQuotesApiException;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

readonly class KanyeQuotesDriver implements QuotesDriver
{
    private const CACHE_VALUE = 'kanye-quotes';

    public function __construct(private string $apiUrl) {}

    public function get(): Collection
    {
        $cachedQuotes = Cache::get(self::CACHE_VALUE);

        if ($cachedQuotes !== null) {
            return $cachedQuotes;
        }

        $quotesResponse = Http::pool(fn (Pool $pool) => [
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
            $pool->get($this->apiUrl),
        ]);

        $quotes = [];

        foreach ($quotesResponse as $quoteResponse) {
            if ($quoteResponse->ok() === false) {
                throw new KanyeQuotesApiException($quoteResponse->getBody()->getContents());
            }

            $quotes[] = $quoteResponse->json('quote');
        }

        $quotesCollection = collect($quotes);

        Cache::set(
            key: self::CACHE_VALUE,
            value: $quotesCollection,
            ttl: 120
        );

        return $quotesCollection;
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_VALUE);
    }
}
