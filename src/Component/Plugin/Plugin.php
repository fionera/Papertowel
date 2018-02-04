<?php
/**
 * Coded by fionera.
 */


namespace Papertowel\Component\Plugin;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Plugin implements PluginInterface
{
    public function onInstall(ContainerInterface $container): void
    {
        // Nothing to do
    }

    public function onEnable(ContainerInterface $container): void
    {
        // Nothing to do
    }

    public function onDisable(ContainerInterface $container): void
    {
        // Nothing to do
    }

    public function onUninstall(ContainerInterface $container): void
    {
        // Nothing to do
    }
}