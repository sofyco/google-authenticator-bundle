<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\App\Repository;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Repository\UserRepositoryInterface;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserRepository implements UserRepositoryInterface
{
    public function loadByIdentifier(string $identifier): ?UserInterface
    {
        if ('khaperets' === $identifier) {
            return new User('khaperets', ['ROLE_USER']);
        }

        return null;
    }
}
