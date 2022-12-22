<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class User implements UserInterface
{
    public function __construct(private string $userIdentifier, private array $roles = [])
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }
}
