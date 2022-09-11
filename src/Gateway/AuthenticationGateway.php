<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Gateway;

use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Userinfo;
use Google\Service\PeopleService;

final class AuthenticationGateway
{
    private readonly Oauth2 $oauth;

    public function __construct(private readonly Client $client)
    {
        $this->oauth = new Oauth2($this->client);
    }

    public function getAuthenticationUri(): string
    {
        return $this->client->createAuthUrl([PeopleService::USERINFO_EMAIL, PeopleService::USERINFO_PROFILE]);
    }

    public function getUserInfo(string $code): Userinfo
    {
        $this->client->fetchAccessTokenWithAuthCode($code);

        return $this->oauth->userinfo->get();
    }
}
