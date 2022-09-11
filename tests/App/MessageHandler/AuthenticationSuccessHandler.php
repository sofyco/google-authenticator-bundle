<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\App\MessageHandler;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Message\AuthenticationSuccess;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AuthenticationSuccessHandler
{
    public function __invoke(AuthenticationSuccess $authenticationSuccess): array
    {
        return ['id' => $authenticationSuccess->userIdentifier];
    }
}
