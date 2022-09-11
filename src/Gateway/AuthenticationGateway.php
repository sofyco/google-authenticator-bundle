<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Gateway;

use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Userinfo;
use Google\Service\PeopleService;

final class AuthenticationGateway
{
    public function __construct(private readonly Client $client, private readonly Oauth2 $oauth)
    {
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
