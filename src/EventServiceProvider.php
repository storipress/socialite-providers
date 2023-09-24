<?php

declare(strict_types=1);

namespace Storipress\SocialiteProviders;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Storipress\SocialiteProviders\Webflow\WebflowSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SocialiteWasCalled::class => [
            WebflowSocialite::class,
        ],
    ];
}
