<?php

declare(strict_types=1);

namespace App\Services\Quotes\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KanyeQuotesApiException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json(
            data: [
                'message' => 'There was an error fetching Kanye quotes. Please try again.'
            ],
            status: Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}
