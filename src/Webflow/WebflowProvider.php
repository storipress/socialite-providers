<?php

declare(strict_types=1);

namespace Storipress\SocialiteProviders\Webflow;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

/**
 * @phpstan-type WebflowUser array{
 *     id: string,
 *     email: string,
 *     firstName?: string,
 *     lastName?: string,
 * }
 */
class WebflowProvider extends AbstractProvider
{
    public const IDENTIFIER = 'WEBFLOW';

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            'https://webflow.com/oauth/authorize',
            $state,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl(): string
    {
        return 'https://api.webflow.com/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     *
     * @return WebflowUser
     *
     * @throws GuzzleException
     */
    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get(
            'https://api.webflow.com/v2/token/authorized_by',
            [
                RequestOptions::HEADERS => [
                    'Authorization' => sprintf('Bearer %s', $token),
                ],
            ],
        );

        /** @var WebflowUser $data */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @param  WebflowUser  $user
     */
    protected function mapUserToObject(array $user): User
    {
        $firstName = $user['firstName'] ?? '';

        $lastName = $user['lastName'] ?? '';

        $name = trim(sprintf('%s %s', $firstName, $lastName));

        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => null,
            'name' => $name ?: null,
            'firstName' => $firstName ?: null,
            'lastName' => $lastName ?: null,
            'email' => $user['email'],
            'avatar' => null,
        ]);
    }
}
