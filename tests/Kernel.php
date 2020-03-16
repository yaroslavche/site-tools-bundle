<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Yaroslavche\SiteToolsBundle\YaroslavcheSiteToolsBundle;

/**
 * Class Kernel
 * @package Yaroslavche\SiteToolsBundle\Tests
 */
class Kernel extends SymfonyKernel
{
    use MicroKernelTrait;

    /**
     * Kernel constructor.
     *
     * @param string $env
     * @param bool $debug
     */
    public function __construct(string $env = 'test', bool $debug = true)
    {
        parent::__construct($env, $debug);
    }

    public function registerBundles()
    {
        return [
            new YaroslavcheSiteToolsBundle(),
            new FrameworkBundle(),
        ];
    }

    public function getCacheDir()
    {
        return __DIR__ . '/cache/' . spl_object_hash($this);
    }

    /**
     * @return array
     */
    public static function getBundleConfig(): array
    {
        return [
            'host' => 'localhost',
        ];
    }

    /**
     * @param RouteCollectionBuilder $routes
     * @throws LoaderLoadException
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
    }

    /**
     * @param ContainerBuilder $c
     * @param LoaderInterface $loader
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension(
            'framework',
            [
                'secret' => 'test'
            ]
        );
        $c->loadFromExtension('yaroslavche_site_tools', static::getBundleConfig());
    }
}
