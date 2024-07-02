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

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new EnsureApiKeyIsValid();
    }

    /**
     * @return void
     */
    public function testCorrectApiKeyPasses(): void
    {
        $key = 'secret';

        Config::set('auth.api_key', $key);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('header')
            ->with('X-API-KEY')
            ->andReturn($key)
            ->once();

        $responseContent = 'Passed middleware';

        $next = function ()  use ($responseContent) {
            return response($responseContent);
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($responseContent, $response->getContent());
    }

    /**
     * @return void
     */
    public function testInvalidApiKeyFails(): void
    {
        $key = 'incorrect-key';

        Config::set('auth.api_key', 'correct-key');

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('header')
            ->with('X-API-KEY')
            ->andReturn($key)
            ->once();

        $responseContent = 'Passed middleware';

        $next = function ()  use ($responseContent) {
            return response($responseContent);
        };

        /** @var JsonResponse $response */
        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame('Wrong API key', $response->getData()->message);
    }
}
