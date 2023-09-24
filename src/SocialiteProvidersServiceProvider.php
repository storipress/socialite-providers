<?php

declare(strict_types=1);

namespace Storipress\SocialiteProviders;

use Illuminate\Support\ServiceProvider;

class SocialiteProvidersServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }
}
