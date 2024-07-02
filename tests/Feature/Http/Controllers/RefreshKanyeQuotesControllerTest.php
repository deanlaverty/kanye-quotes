<?php

declare(strict_types=1);

namespace Feature\Http\Controllers;

use App\Facades\Quote;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RefreshKanyeQuotesControllerTest extends TestCase
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
    public function testQuotesAreRefreshedCorrectly(): void
    {
        $apiKey = Config::get('auth.api_key');
        $quote = $this->faker->sentence();
        $quotes = collect(array_fill(0, 5, $quote));

        Quote::shouldReceive('clearCache')
            ->once()
            ->andReturnNull();

        Quote::shouldReceive('get')
            ->once()
            ->andReturn($quotes);

        $response = $this->get(
            route('kanye-quotes-refresh'),
            ['X-API-KEY' => $apiKey]
        );

        $response->assertSuccessful()
            ->assertJsonCount(5)
            ->assertJson($quotes->toArray());
    }

    /**
     * @return void
     */
    public function testEndpointIsUnauthorisedWithIncorrectKey(): void
    {
        $apiKey = 'incorrect-api-key';

        Quote::shouldReceive('clearCache')
            ->never();

        Quote::shouldReceive('get')
            ->never();

        $response = $this->get(
            route('kanye-quotes-get'),
            ['X-API-KEY' => $apiKey]
        );

        $response->assertUnauthorized();
    }
}
