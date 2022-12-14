<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Message;

final readonly class AuthenticationSuccess
{
    public function __construct(public string $userIdentifier, public string $ip)
    {
    }
}
