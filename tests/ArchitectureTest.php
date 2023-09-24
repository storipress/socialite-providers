<?php

test('There are no debugging statements remaining in our code.')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'echo'])
    ->not()
    ->toBeUsed();

test('Strict typing must be enforced in the code.')
    ->expect('Storipress\SocialiteProviders')
    ->toUseStrictTypes();

test('The code should not utilize the "final" keyword.')
    ->expect('Storipress\SocialiteProviders')
    ->not()
    ->toBeFinal();
