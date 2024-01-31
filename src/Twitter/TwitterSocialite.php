<?php

declare(strict_types=1);

namespace Storipress\SocialiteProviders\Twitter;

use SocialiteProviders\Manager\SocialiteWasCalled;

class TwitterSocialite
{
    /**
     * Register the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite(
            'twitter-storipress',
            TwitterProvider::class,
        );
    }
}
