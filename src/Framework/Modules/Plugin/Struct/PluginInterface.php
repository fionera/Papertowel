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
    public function getAuthor(): string;

    /**
     * @return float
     */
    public function getVersion(): float;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void;

    public function onInstall(): void;

    public function onEnable(): void;

    public function onDisable(): void;

    public function onUninstall(): void;
}
