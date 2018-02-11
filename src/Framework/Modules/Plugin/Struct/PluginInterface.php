<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin\Struct;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

interface PluginInterface extends BundleInterface
{
    /**
     * @return string
     */
    public function getAuthor() : string;

    /**
     * @return float
     */
    public function getVersion() : float;

    /**
     * @return string
     */
    public function getDescription() : string;

    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void;

    /**
     * @param ContainerInterface $container
     */
    public function onInstall(ContainerInterface $container) : void;

    /**
     * @param ContainerInterface $container
     */
    public function onEnable(ContainerInterface $container) : void;

    /**
     * @param ContainerInterface $container
     */
    public function onDisable(ContainerInterface $container) : void;

    /**
     * @param ContainerInterface $container
     */
    public function onUninstall(ContainerInterface $container) : void;
}