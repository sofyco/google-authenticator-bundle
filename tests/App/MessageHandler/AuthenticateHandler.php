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
    public const VALID_CODE = 'valid';
    public const INVALID_CODE = 'invalid';
    public const INVALID_CODE_MESSAGE = 'security.google.auth.code.invalid';

    public function __invoke(Authenticate $authenticate): ?UserInterface
    {
        if (self::INVALID_CODE === $authenticate->token) {
            throw new CustomUserMessageAuthenticationException(self::INVALID_CODE_MESSAGE);
        }

        if (self::VALID_CODE === $authenticate->token) {
            return new InMemoryUser('sofyco', null, ['ROLE_USER']);
        }

        return null;
    }
}
