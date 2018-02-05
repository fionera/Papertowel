<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin\Struct;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Plugin implements PluginInterface
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

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