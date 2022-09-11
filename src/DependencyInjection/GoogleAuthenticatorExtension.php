<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\DependencyInjection;

use Google\Client;
use Google\Service\Oauth2;
use Google\Service\PeopleService;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Gateway\AuthenticationGateway;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Security\EntryPoint\GoogleRedirectEntryPoint;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Guard\GoogleAuthenticator;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Security\Provider\UserProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class GoogleAuthenticatorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $userProvider = new Definition(UserProvider::class);
        $userProvider->setAutowired(true);
        $container->setDefinition(UserProvider::class, $userProvider);

        $oauth = new Definition(Oauth2::class);
        $oauth->setAutowired(true);
        $container->setDefinition(Oauth2::class, $oauth);

        $client = new Definition(Client::class);
        $client->addMethodCall('addScope', [PeopleService::USERINFO_EMAIL]);
        $client->addMethodCall('addScope', [PeopleService::USERINFO_PROFILE]);
        $client->addMethodCall('setAccessType', ['offline']);
        $client->addMethodCall('setIncludeGrantedScopes', [true]);
        $client->addMethodCall('setClientId', ['%env(GOOGLE_CLIENT_ID)%']);
        $client->addMethodCall('setClientSecret', ['%env(GOOGLE_CLIENT_SECRET)%']);
        $client->addMethodCall('setRedirectUri', ['%env(GOOGLE_REDIRECT_URL)%']);
        $container->setDefinition(Client::class, $client);

        $gateway = new Definition(AuthenticationGateway::class);
        $gateway->setAutowired(true);
        $container->setDefinition(AuthenticationGateway::class, $gateway);

        $entryPoint = new Definition(GoogleRedirectEntryPoint::class);
        $entryPoint->setAutowired(true);
        $container->setDefinition(GoogleRedirectEntryPoint::class, $entryPoint);

        $authenticator = new Definition(GoogleAuthenticator::class);
        $authenticator->setAutowired(true);
        $container->setDefinition(GoogleAuthenticator::class, $authenticator);
    }
}
