<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\Security\Guard;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Guard\GoogleAuthenticator;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\App\MessageHandler\AuthenticateHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GoogleAuthenticatorTest extends WebTestCase
{
    public function testInvalidToken(): void
    {
        $response = $this->sendRequest(token: AuthenticateHandler::INVALID_TOKEN);

        self::assertSame(\json_encode(['message' => AuthenticateHandler::INVALID_TOKEN_MESSAGE]), (string) $response->getContent());
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testUnexpectedUser(): void
    {
        $response = $this->sendRequest(token: 'foo');

        self::assertSame(\json_encode(['message' => GoogleAuthenticator::FAILURE_MESSAGE]), (string) $response->getContent());
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAuthenticationSuccess(): void
    {
        $response = $this->sendRequest(token: AuthenticateHandler::VALID_TOKEN);

        self::assertSame(\json_encode(['id' => 'sofyco']), (string) $response->getContent());
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    private function sendRequest(string $token): Response
    {
        $client = self::createClient();

        $client->request(Request::METHOD_POST, '/security/google', ['token' => $token]);

        return $client->getResponse();
    }
}
