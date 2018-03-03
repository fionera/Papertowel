<?php
/**
 * Coded by fionera.
 */


namespace Papertowel\Framework\Modules\Plugin;

use Papertowel\Framework\Entity\Plugin\Plugin;
use Papertowel\Framework\Entity\Plugin\PluginState;
use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Plugin\Struct\PluginInterface;
use Papertowel\Framework\Modules\Website\Struct\WebsiteInterface;
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
     * @var WebsiteInterface
     */
    private $currentWebsite;

    /**
     * PluginManager constructor.
     * @param ContainerInterface $container
     * @param RegistryInterface $registry
     * @param WebsiteInterface|null $currentWebsite
     */
    public function __construct(ContainerInterface $container, RegistryInterface $registry, ?WebsiteInterface $currentWebsite)
    {
        $this->container = $container;
        $this->registry = $registry;
        $this->currentWebsite = $currentWebsite;
    }

    public function enablePlugin(PluginInterface $plugin, ?WebsiteInterface $website = null): void
    {
        if ($website === null) {
            if ($this->currentWebsite === null) {
                throw new \RuntimeException('Could not found Website');
            }

            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            return; //TODO: Throw Exception
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $pluginEntity, 'website' => $website]);

        if ($pluginState === null || !$pluginState->isInstalled() || $pluginState->isEnabled()) {
            return;
        }

        $plugin->onEnable();

        $pluginState->setEnabled(true);

        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }

    public function disablePlugin(PluginInterface $plugin, ?WebsiteInterface $website = null): void
    {
        if ($website === null) {
            if ($this->currentWebsite === null) {
                throw new \RuntimeException('Could not found Website');
            }

            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            return; //TODO: Throw Exception
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $pluginEntity, 'website' => $website]);

        if ($pluginState === null || !$pluginState->isInstalled() || !$pluginState->isEnabled()) {
            return;
        }

        $plugin->onDisable($this->container);

        $pluginState->setEnabled(false);

        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }

    public function installPlugin(PluginInterface $plugin, ?WebsiteInterface $website = null): void
    {
        if ($website === null) {
            if ($this->currentWebsite === null) {
                throw new \RuntimeException('Could not found Website');
            }

            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            $pluginEntity = new Plugin($plugin->getName(), $plugin->getVersion());
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $pluginEntity, 'website' => $website]);
        if ($pluginState === null) {
            $pluginState = new PluginState($website, $pluginEntity);
        }

        if ($pluginState->isInstalled() || $pluginState->isEnabled()) {
            return;
        }

        $plugin->onInstall($this->container);

        $pluginState->setInstalled(true);

        $this->registry->getManager()->persist($pluginEntity);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }

    public function uninstallPlugin(PluginInterface $plugin, ?WebsiteInterface $website = null): void
    {
        if ($website === null) {
            if ($this->currentWebsite === null) {
                throw new \RuntimeException('Could not found Website');
            }

            $website = $this->currentWebsite;
        }

        $pluginEntity = $this->registry->getRepository(Plugin::class)->findOneBy(['name' => $plugin->getName()]);
        if ($pluginEntity === null) {
            return; //TODO: Exception?
        }

        /** @var PluginState|null $pluginState */
        $pluginState = $this->registry->getRepository(PluginState::class)->findOneBy(['plugin' => $pluginEntity, 'website' => $website]);
        if ($pluginState === null) {
            return; //TODO: Exception?
        }

        if (!$pluginState->isInstalled() || $pluginState->isEnabled()) {
            return;
        }

        $plugin->onUninstall();

        $pluginState->setInstalled(false);

        $this->registry->getManager()->persist($pluginState);
        $this->registry->getManager()->flush(); //TODO: Remove maybe?
    }
}
