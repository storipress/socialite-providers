<?php

declare(strict_types=1);

namespace Storipress\SocialiteProviders\Webflow;

use SocialiteProviders\Manager\SocialiteWasCalled;

class WebflowSocialite
{
    /**
     * Register the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite(
            'webflow',
            WebflowProvider::class,
        );
    }
}
