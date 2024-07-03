<?php

declare(strict_types=1);

namespace Feature\Http\Controllers;

use App\Facades\Quote;
use App\Services\Quotes\Exceptions\KanyeQuotesApiException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KanyeQuotesControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function testQuotesAreReturnedCorrectly(): void
    {
        $apiKey = Config::get('auth.api_key');
        $quote = $this->faker->sentence();
        $quotes = collect(array_fill(0, 5, $quote));

        Quote::shouldReceive('get')
            ->once()
            ->andReturn($quotes);

        $response = $this->get(
            route('kanye-quotes-get'),
            ['X-API-KEY' => $apiKey]
        );

        $response->assertSuccessful()
            ->assertJsonCount(5)
            ->assertJson($quotes->toArray());
    }

    public function testErrorMessageReturnedWhenApiHasError(): void
    {
        $apiKey = Config::get('auth.api_key');

        Quote::shouldReceive('get')
            ->once()
            ->andThrow(KanyeQuotesApiException::class);

        $response = $this->get(
            route('kanye-quotes-get'),
            ['X-API-KEY' => $apiKey]
        );

        $response->assertServiceUnavailable();
    }

    public function testEndpointIsUnauthorisedWithIncorrectKey(): void
    {
        $apiKey = 'incorrect-api-key';

        Quote::shouldReceive('get')
            ->never();

        $response = $this->get(
            route('kanye-quotes-get'),
            ['X-API-KEY' => $apiKey]
        );

        $response->assertUnauthorized();
    }
}
