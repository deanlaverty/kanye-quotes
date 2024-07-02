<?php

declare(strict_types=1);

namespace App\Services\Quotes;

use Illuminate\Support\Manager;

class QuotesManager extends Manager
{
    public function createKanyeDriver(): QuotesDriver
    {
        return resolve(KanyeQuotesDriver::class);
    }

    public function getDefaultDriver(): string
    {
        return $this->config->get('quotes.driver', 'kanye');
    }
}
