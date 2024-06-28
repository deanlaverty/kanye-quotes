<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\Quotes\QuotesManager;
use Illuminate\Support\Facades\Facade;

class Quote extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return QuotesManager::class;
    }
}
