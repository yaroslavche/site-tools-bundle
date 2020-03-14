<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class YaroslavcheSiteToolsExtension
 * @package Yaroslavche\SiteToolsBundle\DependencyInjection
 */
class YaroslavcheSiteToolsExtension extends Extension
{
    public const EXTENSION_ALIAS = 'yaroslavche_site_tools';

    /**
     * Loads a specific configuration.
     *
     * @param array<mixed> $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

/*
        $definition = $container->getDefinition(sprintf('%s.service.online', $this->getAlias()));
        $definition->setArguments($config);
*/
    }

    /** @return string */
    public function getAlias(): string
    {
        return static::EXTENSION_ALIAS;
    }
}
