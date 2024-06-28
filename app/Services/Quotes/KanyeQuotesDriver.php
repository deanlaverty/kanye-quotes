<?php

declare(strict_types=1);

namespace App\Services\Quotes;

use Illuminate\Support\Collection;

class KanyeQuotesDriver implements QuotesDriver
{
    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return collect([
            'quotes' => [
                'Test quote',
            ],
        ]);
    }
}
