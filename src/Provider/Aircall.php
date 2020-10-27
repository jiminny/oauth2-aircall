<?php

namespace Jiminny\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Aircall extends AbstractProvider
{
    use ArrayAccessorTrait,
        BearerAuthorizationTrait;

    /**
     *
     * @var string
     */
    protected $host = 'https://dashboard-v2.aircall.io';


    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getHost() . '/oauth/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getHost() . '/oauth/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.aircall.io/v1/users/'.$token->getResourceOwnerId();
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Returns a cleaned host.
     *
     * @return string
     */
    public function getHost()
    {
        return rtrim($this->host, '/');
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ' '
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        // At the time of initial implementation the possible error payloads returned
        // by Salesloft were not very well documented. This method will need some
        // improvement as the API continues to mature.
        if ($response->getStatusCode() != 200) {
            throw new IdentityProviderException('Unexpected response code', $response->getStatusCode(), $response);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param object $response
     * @param AccessToken $token
     * @return AircallResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new AircallResourceOwner($response);
    }
}
