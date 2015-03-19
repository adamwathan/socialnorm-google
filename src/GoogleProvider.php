<?php namespace SocialNorm\Google;

use SocialNorm\Exceptions\InvalidAuthorizationCodeException;
use SocialNorm\Providers\OAuth2Provider;

class GoogleProvider extends OAuth2Provider
{
    protected $authorizeUrl = "https://accounts.google.com/o/oauth2/auth";
    protected $accessTokenUrl = "https://accounts.google.com/o/oauth2/token";
    protected $userDataUrl = "https://www.googleapis.com/userinfo/v2/me";
    protected $scope = [
        'https://www.googleapis.com/auth/userinfo.profile',
        'https://www.googleapis.com/auth/userinfo.email',
    ];

    protected $headers = [
        'authorize' => [],
        'access_token' => [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        'user_details' => [],
    ];

    protected function compileScopes()
    {
        return implode(' ', $this->scope);
    }

    protected function getAuthorizeUrl()
    {
        return $this->authorizeUrl;
    }

    protected function getAccessTokenBaseUrl()
    {
        return $this->accessTokenUrl;
    }

    protected function getUserDataUrl()
    {
        return $this->userDataUrl;
    }

    protected function parseTokenResponse($response)
    {
        return $this->parseJsonTokenResponse($response);
    }

    protected function parseUserDataResponse($response)
    {
        return json_decode($response, true);
    }

    protected function userId()
    {
        return $this->getProviderUserData('id');
    }

    protected function nickname()
    {
        return $this->getProviderUserData('email');
    }

    protected function fullName()
    {
        return $this->getProviderUserData('given_name') . ' ' . $this->getProviderUserData('family_name');
    }

    protected function avatar()
    {
        return $this->getProviderUserData('picture');
    }

    protected function email()
    {
        return $this->getProviderUserData('email');
    }
}
