<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\Security\Guard;

use Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Guard\GoogleAuthenticator;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\App\MessageHandler\AuthenticateHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GoogleAuthenticatorTest extends WebTestCase
{
    public function testInvalidCode(): void
    {
        $response = $this->sendRequest(code: AuthenticateHandler::INVALID_CODE);

        self::assertSame(\json_encode(['message' => AuthenticateHandler::INVALID_CODE_MESSAGE]), (string) $response->getContent());
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testUnexpectedUser(): void
    {
        $response = $this->sendRequest(code: 'foo');

        self::assertSame(\json_encode(['message' => GoogleAuthenticator::FAILURE_MESSAGE]), (string) $response->getContent());
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAuthenticationSuccess(): void
    {
        $response = $this->sendRequest(code: AuthenticateHandler::VALID_CODE);

        self::assertSame(\json_encode(['id' => 'khaperets']), (string) $response->getContent());
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    private function sendRequest(string $code): Response
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/security/google', ['code' => $code]);

        return $client->getResponse();
    }
}
