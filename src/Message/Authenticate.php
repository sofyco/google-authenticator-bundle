<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Message;

final readonly class Authenticate
{
    public function __construct(public string $token)
    {
    }
}
