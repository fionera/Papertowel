<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin\Struct;

use Symfony\Component\HttpKernel\Bundle\Bundle;

abstract class Plugin extends Bundle implements PluginInterface
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var float
     */
    protected $version;

    /**
     * @var string
     */
    protected $description;

    /**
     * @return bool
     */
    final public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    final public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return float
     */
    public function getVersion(): float
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function onInstall(): void
    {
        // Nothing to do
    }

    public function onEnable(): void
    {
        // Nothing to do
    }

    public function onDisable(): void
    {
        // Nothing to do
    }

    public function onUninstall(): void
    {
        // Nothing to do
    }
}
