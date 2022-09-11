<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Message;

final class AuthenticationSuccess
{
    public function __construct(public readonly string $userIdentifier)
    {
    }
}
