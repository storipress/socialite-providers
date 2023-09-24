<?php

declare(strict_types=1);

namespace Storipress\SocialiteProviders;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\ServiceProvider as SocialiteServiceProvider;

class SocialiteProvidersServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(SocialiteServiceProvider::class);

        $this->app->register(EventServiceProvider::class);
    }
}
