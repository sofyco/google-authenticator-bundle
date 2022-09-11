<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Security\EntryPoint;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Gateway\AuthenticationGateway;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class GoogleRedirectEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(private readonly AuthenticationGateway $authenticationGateway)
    {
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        return new RedirectResponse($this->authenticationGateway->getAuthenticationUri());
    }
}
