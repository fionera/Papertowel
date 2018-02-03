<?php

namespace App\EventSubscriber;

use App\Component\Plugin\PluginInterface;
use App\Entity\Website;
use App\Papertowel;
use App\Service\Plugin\PluginProvider;
use App\Service\Theme\ThemeProvider;
use App\Service\Website\WebsiteProvider;
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
                $pluginList = $this->pluginProvider->getPluginNames();
                $websitePluginStates = $requestedWebsite->getPluginStates();

                foreach ($websitePluginStates as $pluginState) {
                    if (!$pluginState->isInstalled() || !$pluginState->isEnabled()) {
                        continue;
                    }

                    $plugin = $pluginState->getPlugin();

                    if (!isset($pluginList[$plugin->getName()])) {
                        throw new \RuntimeException('Missing Plugin: ' . $plugin->getName());
                    }

                    if ($pluginState->isInstalled() || $pluginState->isEnabled()) {
                        $this->pluginProvider->loadPlugin($pluginState->getPlugin()->getName());
                    }
                }

                array_map(function (PluginInterface $plugin) {
                    echo $plugin->getName();
                }, $this->pluginProvider->getPluginList());
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
