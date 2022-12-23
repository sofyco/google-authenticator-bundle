<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Guard;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Message\Authenticate;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Message\AuthenticationSuccess;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class GoogleAuthenticator extends AbstractAuthenticator
{
    public const FAILURE_MESSAGE = 'security.google.auth.error';

    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->request->has('token');
    }

    public function authenticate(Request $request): Passport
    {
        try {
            $envelope = $this->messageBus->dispatch(new Authenticate((string) $request->request->get('token')));
        } catch (HandlerFailedException $exception) {
            throw new CustomUserMessageAuthenticationException($exception->getNestedExceptions()[0]->getMessage());
        }

        $handledStamp = $envelope->last(HandledStamp::class);

        if (null === $handledStamp) {
            throw new CustomUserMessageAuthenticationException(self::FAILURE_MESSAGE);
        }

        $result = $handledStamp->getResult();

        if (false === \is_object($result) || !$result instanceof UserInterface) {
            throw new CustomUserMessageAuthenticationException(self::FAILURE_MESSAGE);
        }

        return new SelfValidatingPassport(new UserBadge($result->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $message = new AuthenticationSuccess($token->getUserIdentifier(), (string) $request->getClientIp());
        $envelope = $this->messageBus->dispatch($message);

        $handledStamp = $envelope->last(HandledStamp::class);

        if (null === $handledStamp) {
            throw new CustomUserMessageAuthenticationException(self::FAILURE_MESSAGE);
        }

        return new JsonResponse($handledStamp->getResult());
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}

