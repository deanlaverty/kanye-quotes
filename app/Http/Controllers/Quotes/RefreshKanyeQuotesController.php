<?php

declare(strict_types=1);

namespace App\Http\Controllers\Quotes;

use App\Facades\Quote;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class RefreshKanyeQuotesController extends Controller
{
    public function __invoke(): JsonResponse
    {
        Quote::clearCache();

        return response()->json(
            Quote::get()
        );
    }
}
