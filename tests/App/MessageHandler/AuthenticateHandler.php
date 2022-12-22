<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\App\MessageHandler;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Message\Authenticate;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsMessageHandler]
final class AuthenticateHandler
{
    public const VALID_TOKEN = 'valid';
    public const INVALID_TOKEN = 'invalid';
    public const INVALID_TOKEN_MESSAGE = 'security.google.auth.token.invalid';

    public function __invoke(Authenticate $authenticate): ?UserInterface
    {
        if (self::INVALID_TOKEN === $authenticate->token) {
            throw new CustomUserMessageAuthenticationException(self::INVALID_TOKEN_MESSAGE);
        }

        if (self::VALID_TOKEN === $authenticate->token) {
            return new InMemoryUser('sofyco', null, ['ROLE_USER']);
        }

        return null;
    }
}
