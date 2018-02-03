<?php
/**
 * Coded by fionera.
 */


namespace App\Service\Plugin;


use App\Component\Plugin\PluginInterface;
use App\Entity\Plugin;
use App\Entity\PluginState;
use App\Entity\Website;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var Website
     */
    private $currentWebsite;

    /**
     * PluginManager constructor.
     * @param ContainerInterface $container
     * @param RegistryInterface $registry
     */
    public function __construct(ContainerInterface $container, RegistryInterface $registry, Website $currentWebsite)
    {
        $this->container = $container;
        $this->registry = $registry;
        $this->currentWebsite = $currentWebsite;
    }

    public function enablePlugin(PluginInterface $plugin, Website $website = null): void
    {
        if ($website === null) {
            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            return; //TODO: Throw Exception
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $plugin, 'website' => $website]);

        if ($pluginState === null || !$pluginState->isInstalled() || $pluginState->isEnabled()) {
            return;
        }

        $plugin->onEnable($this->container);

        $pluginState->setEnabled(true);

        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }

    public function disablePlugin(PluginInterface $plugin, Website $website = null): void
    {
        if ($website === null) {
            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            return; //TODO: Throw Exception
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $plugin, 'website' => $website]);

        if ($pluginState === null || !$pluginState->isInstalled() || !$pluginState->isEnabled()) {
            return;
        }

        $plugin->onDisable($this->container);

        $pluginState->setEnabled(false);

        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }

    public function installPlugin(PluginInterface $plugin, Website $website = null): void
    {
        if ($website === null) {
            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            $pluginEntity = new Plugin($plugin->getName(), $plugin->getVersion());
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $plugin, 'website' => $website]);
        if ($pluginState === null) {
            $pluginState = new PluginState($website, $pluginEntity);
        }

        if ($pluginState->isInstalled() || $pluginState->isEnabled()) {
            return;
        }

        $plugin->onInstall($this->container);

        $pluginState->setInstalled(true);

        $this->registry->getManager()->persist($pluginEntity);
        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }

    public function uninstallPlugin(PluginInterface $plugin, Website $website = null): void
    {
        if ($website === null) {
            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            return; //TODO: Exception?
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $plugin, 'website' => $website]);
        if ($pluginState === null) {
            return; //TODO: Exception?
        }

        if (!$pluginState->isInstalled() || $pluginState->isEnabled()) {
            return;
        }

        $plugin->onUninstall($this->container);

        $pluginState->setInstalled(false);

        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }
}