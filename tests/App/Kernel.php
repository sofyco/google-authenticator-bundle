<?php declare(strict_types=1);

namespace Sofyco\Bundle\GoogleAuthenticatorBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        yield new \Symfony\Bundle\SecurityBundle\SecurityBundle();
        yield new \Sofyco\Bundle\JwtAuthenticatorBundle\JwtAuthenticatorBundle();
        yield new \Sofyco\Bundle\GoogleAuthenticatorBundle\GoogleAuthenticatorBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('config/config.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('security_google', '/security/google')->controller(__CLASS__);
    }

    public function __invoke(): void
    {
    }
}
