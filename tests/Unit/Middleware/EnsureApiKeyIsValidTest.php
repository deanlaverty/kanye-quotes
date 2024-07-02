<?php

declare(strict_types=1);

namespace Tests\Unit\Middleware;

use App\Http\Middleware\EnsureApiKeyIsValid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class EnsureApiKeyIsValidTest extends TestCase
{
    protected EnsureApiKeyIsValid $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new EnsureApiKeyIsValid();
    }

    public function testCorrectApiKeyPasses(): void
    {
        $key = 'secret';

        Config::set('auth.api_key', $key);

        $request = $this->mockRequest($key);

        $responseContent = 'Passed middleware';

        $next = function () use ($responseContent) {
            return response($responseContent);
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($responseContent, $response->getContent());
    }

    public function testInvalidApiKeyFails(): void
    {
        $key = 'incorrect-key';

        Config::set('auth.api_key', 'correct-key');

        $request = $this->mockRequest($key);

        $responseContent = 'Passed middleware';

        $next = function () use ($responseContent) {
            return response($responseContent);
        };

        /** @var JsonResponse $response */
        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame('Wrong API key', $response->getData()->message);
    }

    private function mockRequest(string $apiKey): Mockery\MockInterface
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('header')
            ->with('X-API-KEY')
            ->andReturn($apiKey)
            ->once();

        return $request;
    }
}
