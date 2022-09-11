<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Provider;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Repository\UserRepositoryInterface;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Entity\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public const USER_NOT_FOUND_MESSAGE = 'security.google.auth.user.notFound';

    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->loadByIdentifier($identifier);

        if (null === $user) {
            throw new UserNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
