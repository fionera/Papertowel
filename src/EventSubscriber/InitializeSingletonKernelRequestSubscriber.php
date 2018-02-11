<?php

namespace Papertowel\EventSubscriber;

use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Plugin\PluginProvider;
use Papertowel\Framework\Modules\Plugin\Struct\PluginInterface;
use Papertowel\Framework\Modules\Theme\ThemeProvider;
use Papertowel\Framework\Modules\Website\WebsiteProvider;
use Papertowel\Papertowel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class InitializeSingletonKernelRequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var WebsiteProvider
     */
    private $websiteProvider;
    /**
     * @var ThemeProvider
     */
    private $themeProvider;

    /**
     * @var boolean
     */
    private $booted;
    /**
     * @var PluginProvider
     */
    private $pluginProvider;

    /**
     * InitializeSingletonKernelRequestSubscriber constructor.
     * @param WebsiteProvider $websiteProvider
     * @param ThemeProvider $themeProvider
     * @param PluginProvider $pluginProvider
     */
    public function __construct(WebsiteProvider $websiteProvider, ThemeProvider $themeProvider, PluginProvider $pluginProvider)
    {
        $this->websiteProvider = $websiteProvider;
        $this->themeProvider = $themeProvider;
        $this->pluginProvider = $pluginProvider;
    }

    /**
     * @param GetResponseEvent $event
     * @throws \Exception
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->booted !== true) {
            global $kernel;
            $container = $kernel->getContainer();

            {
                /** @var Website|null $requestedWebsite */
                $requestedWebsite = $this->websiteProvider->getWebsite($event->getRequest());

                if ($requestedWebsite === null) {
                    throw new \Exception('Website cannot be found');
                }

                $container->set('website', $requestedWebsite);
            }

            {
                $websitePluginStates = $requestedWebsite->getPluginStates();
                $existingPlugins = $this->pluginProvider->getPluginNames();

                foreach ($websitePluginStates as $pluginState) {
                    if (!$pluginState->isInstalled() || !$pluginState->isEnabled()) {
                        continue;
                    }

                    $plugin = $pluginState->getPlugin();

                    if (!in_array($plugin->getName(), $existingPlugins, true)) {
                        throw new \RuntimeException('Missing Plugin: ' . $plugin->getName());
                    }

                    if ($pluginState->isInstalled() || $pluginState->isEnabled()) {
                        $this->pluginProvider->loadPlugin($plugin->getName());
                    }
                }
            }

            {
                $reqestedTheme = $this->themeProvider->getThemeByName($requestedWebsite->getThemeName());

                if ($reqestedTheme === null) {
                    throw new \Exception('Theme cannot be found');
                }

                $container->set('theme', $reqestedTheme);
            }

            Papertowel::setInstance(new Papertowel($container));

            $this->booted = true;
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => ['onKernelRequest', 9001],
        ];
    }

}
