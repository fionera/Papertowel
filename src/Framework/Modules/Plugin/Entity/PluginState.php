<?php

namespace Papertowel\Framework\Modules\Plugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Papertowel\Framework\Modules\Website\Entity\Website;

/**
 * @ORM\Entity
 */
class PluginState
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Papertowel\Entity\Website", inversedBy="pluginStates")
     * @var Website
     */
    private $website;

    /**
     * @ORM\ManyToOne(targetEntity="Papertowel\Entity\Plugin")
     * @ORM\JoinColumn(name="plugin_id", referencedColumnName="id")
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