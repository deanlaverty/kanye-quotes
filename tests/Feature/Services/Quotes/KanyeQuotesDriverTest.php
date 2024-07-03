<?php

declare(strict_types=1);

namespace Feature\Services\Quotes;

use App\Services\Quotes\Exceptions\KanyeQuotesApiException;
use App\Services\Quotes\KanyeQuotesDriver;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KanyeQuotesDriverTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function testQuotesAreReturnedCorrectly(): void
    {
        $quote = $this->faker->sentence();

        Http::fake([
            '*' => Http::response(
                ['quote' => $quote],
            ),
        ]);

        /** @var KanyeQuotesDriver $service */
        $service = resolve(KanyeQuotesDriver::class);
        $quotes = $service->get();

        $expected = collect(array_fill(0, 5, $quote));

        $this->assertEquals($expected, $quotes);

        Http::assertSentCount(5);
    }

    public function testQuotesAreReturnedFromCacheWhenSet(): void
    {
        $quote = $this->faker->sentence();
        $quotes = array_fill(0, 5, $quote);
        $quotesCollection = collect($quotes);

        Cache::shouldReceive('get')
            ->once()
            ->andReturn($quotesCollection);

        Cache::shouldReceive('set')
            ->never();

        /** @var KanyeQuotesDriver $service */
        $service = resolve(KanyeQuotesDriver::class);
        $quotesResponse = $service->get();

        $expected = collect($quotes);

        $this->assertEquals($expected, $quotesResponse);

        Http::assertNothingSent();
    }

    public function testExceptionIsThrownIfErrorWithApi(): void
    {
        $quote = $this->faker->sentence();

        Http::fake([
            '*' => Http::sequence()
                ->push(['quote' => $quote])
                ->push(['quote' => $quote])
                ->push(['quote' => $quote])
                ->push(['quote' => $quote])
                ->push('There was an error', 400),
        ]);

        $this->expectException(KanyeQuotesApiException::class);

        /** @var KanyeQuotesDriver $service */
        $service = resolve(KanyeQuotesDriver::class);
        $service->get();
    }
}
