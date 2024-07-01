<?php

declare(strict_types=1);

namespace Feature\Services\Quotes;

use App\Services\Quotes\KanyeQuotesDriver;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KanyeQuotesDriverTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testQuotesAreReturnedFromCacheWhenSet(): void
    {
        $quote = $this->faker->sentence();
        $quotes = array_fill(0, 5, $quote);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($quotes);

        /** @var KanyeQuotesDriver $service */
        $service = resolve(KanyeQuotesDriver::class);
        $quotesResponse = $service->get();

        $expected = collect($quotes);

        $this->assertEquals($expected, $quotesResponse);

        Http::assertNothingSent();
    }
}
