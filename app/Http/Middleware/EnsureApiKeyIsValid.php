<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headerKey = $request->header('X-API-KEY');

        if ($headerKey !== config('auth.api_key')) {
            return response()->json([
                'error' => true,
                'message' => 'Wrong API key',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
