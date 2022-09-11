<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserRepositoryInterface
{
    public function loadByIdentifier(string $identifier): ?UserInterface;
}
