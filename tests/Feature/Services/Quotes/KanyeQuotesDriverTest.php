<?php

declare(strict_types=1);

namespace Feature\Services\Quotes;

use App\Services\Quotes\KanyeQuotesDriver;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KanyeQuotesDriverTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    /**
     * @return void
     */
    public function testApiCountCallIsCorrect(): void
    {
        Http::fake();

        /** @var KanyeQuotesDriver $service */
        $service = resolve(KanyeQuotesDriver::class);
        $service->get();

        Http::assertSentCount(5);
    }
}
