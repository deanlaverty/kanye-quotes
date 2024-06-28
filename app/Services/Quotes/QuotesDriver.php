<?php

declare(strict_types=1);

namespace App\Services\Quotes;

use Illuminate\Support\Collection;

interface QuotesDriver
{
    public function get(): Collection;
}
