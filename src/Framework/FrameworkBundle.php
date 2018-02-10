<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework;

use Papertowel\Framework\DependencyInjection\FrameworkExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrameworkBundle extends Bundle
{
    protected $name = 'Papertowel';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('services.xml');


    }

    public function getContainerExtension()
    {
        return new FrameworkExtension();
    }

}