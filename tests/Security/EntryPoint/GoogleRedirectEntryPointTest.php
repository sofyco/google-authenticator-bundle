<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\Security\EntryPoint;

use Google\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GoogleRedirectEntryPointTest extends WebTestCase
{
    public function testRedirectResponse(): void
    {
        $response = $this->sendRequest();
        $content = (string) $response->getContent();

        self::assertStringContainsString(Client::OAUTH2_AUTH_URL, $content);
        self::assertStringContainsString('response_type=code', $content);
        self::assertStringContainsString('access_type=offline', $content);
        self::assertStringContainsString('include_granted_scopes=true', $content);
        self::assertStringContainsString('include_granted_scopes=true', $content);
        self::assertStringContainsString(\urlencode('https://localhost/security/google'), $content);
        self::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    private function sendRequest(): Response
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/security/google');

        return $client->getResponse();
    }
}
