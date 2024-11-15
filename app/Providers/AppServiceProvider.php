<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Quotes\KanyeQuotesDriver;
use App\Services\Quotes\QuotesManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: QuotesManager::class,
            concrete: fn (Application $app) => new QuotesManager($app),
        );

        $this->app->bind(
            abstract: KanyeQuotesDriver::class,
            concrete: fn (Application $app) => new KanyeQuotesDriver(config('quotes.api_url'), config('quotes.cache_ttl'))
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
