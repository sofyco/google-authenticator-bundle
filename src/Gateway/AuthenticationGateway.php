<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Gateway;

use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Userinfo;

final readonly class AuthenticationGateway
{
    private Oauth2 $oauth;

    public function __construct(private Client $client)
    {
        $this->oauth = new Oauth2($this->client);
    }

    public function getUserInfo(string $token): Userinfo
    {
        $this->client->setAccessToken($token);

        return $this->oauth->userinfo->get();
    }
}
