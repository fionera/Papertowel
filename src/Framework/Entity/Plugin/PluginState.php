<?php

namespace Papertowel\Framework\Entity\Plugin;

use Doctrine\ORM\Mapping as ORM;
use Papertowel\Framework\Entity\Website\Website;

/**
 * @ORM\Entity
 */
class PluginState
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Papertowel\Framework\Entity\Website\Website", inversedBy="pluginStates")
     * @var Website
     */
    private $website;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Papertowel\Framework\Entity\Plugin\Plugin")
     * @var Plugin
     */
    private $plugin;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $installed;

    /**
     * PluginState constructor.
     * @param Website $website
     * @param Plugin $plugin
     * @param bool $enabled
     * @param bool $installed
     */
    public function __construct(Website $website, Plugin $plugin, bool $enabled = false, bool $installed = false)
    {
        $this->website = $website;
        $this->plugin = $plugin;
        $this->enabled = $enabled;
        $this->installed = $installed;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Website
     */
    public function getWebsite(): Website
    {
        return $this->website;
    }

    /**
     * @param Website $website
     */
    public function setWebsite(Website $website): void
    {
        $this->website = $website;
    }

    /**
     * @return Plugin
     */
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }

    /**
     * @param Plugin $plugin
     */
    public function setPlugin(Plugin $plugin): void
    {
        $this->plugin = $plugin;
    }

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

    /**
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->installed;
    }

    /**
     * @param bool $installed
     */
    public function setInstalled(bool $installed): void
    {
        $this->installed = $installed;
    }
}