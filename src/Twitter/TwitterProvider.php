<?php

declare(strict_types=1);

namespace Storipress\SocialiteProviders\Twitter;

use GuzzleHttp\RequestOptions;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

/**
 * @phpstan-type TwitterUser array{
 *     id: string,
 *     name: string,
 *     username: string,
 *     profile_image_url: string,
 * }
 */
class TwitterProvider extends AbstractProvider
{
    public const IDENTIFIER = 'TWITTER-STORIPRESS';

    /**
     * The scopes being requested.
     *
     * @var array<int, string>
     */
    protected $scopes = [
        'users.read',
        'tweet.read',
    ];

    /**
     * Indicates if PKCE should be used.
     *
     * @var bool
     */
    protected $usesPKCE = true;

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The query encoding format.
     *
     * @var int
     */
    protected $encodingType = PHP_QUERY_RFC3986;

    /**
     * {@inheritdoc}
     */
    public function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            'https://twitter.com/i/oauth2/authorize',
            $state,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl(): string
    {
        return 'https://api.twitter.com/2/oauth2/token';
    }

    /**
     * {@inheritdoc}
     *
     * @return TwitterUser
     */
    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get(
            'https://api.twitter.com/2/users/me',
            [
                RequestOptions::HEADERS => [
                    'Authorization' => sprintf('Bearer %s', $token),
                ],
                RequestOptions::QUERY => [
                    'user.fields' => 'profile_image_url',
                ],
            ],
        );

        /**
         * @var array{
         *     data: TwitterUser,
         * } $data
         */
        $data = json_decode((string) $response->getBody(), true);

        return $data['data'];
    }

    /**
     * {@inheritdoc}
     *
     * @param  TwitterUser  $user
     */
    protected function mapUserToObject(array $user): User
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['name'],
            'avatar' => $user['profile_image_url'],
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @return array{
     *     token_type: string,
     *     expires_in: int,
     *     access_token: string,
     *     scope: string,
     *     refresh_token: string,
     * }
     */
    public function getAccessTokenResponse($code): array
    {
        $response = $this->getHttpClient()->post(
            $this->getTokenUrl(),
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
                RequestOptions::AUTH => [
                    $this->clientId,
                    $this->clientSecret,
                ],
                RequestOptions::FORM_PARAMS => $this->getTokenFields($code),
            ],
        );

        return json_decode((string) $response->getBody(), true); // @phpstan-ignore-line
    }

    /**
     * {@inheritdoc}
     *
     * @return array{
     *      token_type: string,
     *      expires_in: int,
     *      access_token: string,
     *      scope: string,
     *      refresh_token: string,
     *  }
     */
    protected function getRefreshTokenResponse($refreshToken): array
    {
        $response = $this->getHttpClient()->post(
            $this->getTokenUrl(),
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
                RequestOptions::AUTH => [
                    $this->clientId,
                    $this->clientSecret,
                ],
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                    'client_id' => $this->clientId,
                ],
            ],
        );

        return json_decode((string) $response->getBody(), true); // @phpstan-ignore-line
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, string>
     */
    protected function getCodeFields($state = null): array
    {
        return parent::getCodeFields($state);
    }
}
