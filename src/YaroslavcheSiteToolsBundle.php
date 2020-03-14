<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle;

use LogicException;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Yaroslavche\SiteToolsBundle\DependencyInjection\YaroslavcheSiteToolsExtension;

/**
 * Class YaroslavcheSiteToolsBundle
 * @package Yaroslavche\SiteToolsBundle
 */
class YaroslavcheSiteToolsBundle extends Bundle
{
    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface|null The container extension
     *
     * @throws LogicException
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new YaroslavcheSiteToolsExtension();
        }

        return $this->extension;
    }
}
