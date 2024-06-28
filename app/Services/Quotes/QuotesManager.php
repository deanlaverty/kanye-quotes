<?php

declare(strict_types=1);

namespace App\Services\Quotes;

use Illuminate\Support\Manager;

class QuotesManager extends Manager
{
    /**
     * @return QuotesDriver
     */
    public function createKanyeDriver(): QuotesDriver
    {
        return new KanyeQuotesDriver();
    }

    /**
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('quotes.driver', 'kanye');
    }
}
