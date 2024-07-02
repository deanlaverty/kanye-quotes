<?php

declare(strict_types=1);

namespace Tests\Unit\Facades;

use App\Facades\Quote;
use App\Services\Quotes\QuotesManager;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    public function testFacadeReturnsQuoteManager(): void
    {
        $quoteFacade = Quote::getFacadeRoot();

        $this->assertInstanceOf(QuotesManager::class, $quoteFacade);
    }
}
