<?php

declare(strict_types=1);

namespace App\Http\Controllers\Quotes;

use App\Facades\Quote;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class KanyeQuotesController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(
            Quote::get()
        );
    }
}
