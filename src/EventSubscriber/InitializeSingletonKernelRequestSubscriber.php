<?php

namespace Papertowel\EventSubscriber;

use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Plugin\PluginProvider;
use Papertowel\Framework\Modules\Plugin\Struct\PluginInterface;
use Papertowel\Framework\Modules\Theme\Exception\ThemeNotFoundException;
use Papertowel\Framework\Modules\Theme\ThemeProvider;
use Papertowel\Framework\Modules\Website\WebsiteProvider;
use Papertowel\Papertowel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class InitializeSingletonKernelRequestSubscriber
 * @package Papertowel\EventSubscriber
 */
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
     * @var ContainerInterface
     */
    private $container;

    /**
     * InitializeSingletonKernelRequestSubscriber constructor.
     * @param WebsiteProvider $websiteProvider
     * @param ThemeProvider $themeProvider
     * @param ContainerInterface $container
     */
    public function __construct(
        WebsiteProvider $websiteProvider,
        ThemeProvider $themeProvider,
        ContainerInterface $container
    ) {
        $this->websiteProvider = $websiteProvider;
        $this->themeProvider = $themeProvider;
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => ['onKernelRequest', 9001],
        ];
    }

    /**
     * @param GetResponseEvent $event
     * @throws \Exception
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            /** @var Website|null $requestedWebsite */
            $requestedWebsite = $this->websiteProvider->getWebsite($event->getRequest());

            if ($requestedWebsite === null) {
                throw new \RuntimeException('Website cannot be found');
            }

            $this->container->set('website', $requestedWebsite);
            $requestedTheme = $this->themeProvider->getThemeByName($requestedWebsite->getThemeName());

            if ($requestedTheme === null) {
                throw new ThemeNotFoundException('Theme cannot be found');
            }

            $this->container->set('theme', $requestedTheme);

            Papertowel::setInstance(new Papertowel($this->container));
        }
    }
}
