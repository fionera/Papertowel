<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Component\Plugin\Controller;

use Papertowel\Component\Theme\ThemeInterface;
use Papertowel\Component\Theme\ThemeNotFoundException;
use Papertowel\Entity\Language;
use Papertowel\Entity\Website;
use Papertowel\Service\Language\LanguageProvider;
use Papertowel\Service\Theme\ThemeProvider;
use Papertowel\Service\Website\WebsiteProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class PluginController extends Controller
{
    /**
     * @var LanguageProvider
     */
    private $websiteProvider;

    /**
     * @var LanguageProvider
     */
    private $themeProvider;

    /**
     * @var LanguageProvider
     */
    private $languageProvider;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->websiteProvider = $this->container->get('app.service.website.provider');
        $this->themeProvider = $this->container->get('app.service.theme.provider');
        $this->languageProvider = $this->container->get('app.service.language.provider');
    }

    /**
     * @return Website
     * @throws \LogicException
     */
    public function getWebsite(): Website
    {
        return $this->websiteProvider->getWebsite();
    }

    /**
     * @return ThemeInterface
     * @throws \LogicException
     * @throws ThemeNotFoundException
     */
    public function getTheme(): ThemeInterface
    {
        return $this->themeProvider->getThemeByName($this->getWebsite()->getThemeName());
    }

    /**
     * @return Language
     */
    public function getCurrentLanguage(): Language
    {
        return $this->languageProvider->getCurrentLanguage();
    }
}