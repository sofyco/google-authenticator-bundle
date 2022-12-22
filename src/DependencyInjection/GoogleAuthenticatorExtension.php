<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\DependencyInjection;

use Google\Client;
use Google\Service\PeopleService;
use Sofyco\Bundle\GoogleAuthenticatorBundle\Gateway\AuthenticationGateway;
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

        $client = new Definition(Client::class);
        $client->addMethodCall('addScope', [PeopleService::USERINFO_EMAIL]);
        $client->addMethodCall('addScope', [PeopleService::USERINFO_PROFILE]);
        $client->addMethodCall('setAccessType', ['offline']);
        $client->addMethodCall('setIncludeGrantedScopes', [true]);
        $client->addMethodCall('setClientId', ['%env(GOOGLE_CLIENT_ID)%']);
        $client->addMethodCall('setClientSecret', ['%env(GOOGLE_CLIENT_SECRET)%']);
        $container->setDefinition(Client::class, $client);

        $gateway = new Definition(AuthenticationGateway::class);
        $gateway->setAutowired(true);
        $container->setDefinition(AuthenticationGateway::class, $gateway);

        $authenticator = new Definition(GoogleAuthenticator::class);
        $authenticator->setAutowired(true);
        $container->setDefinition(GoogleAuthenticator::class, $authenticator);
    }
}
