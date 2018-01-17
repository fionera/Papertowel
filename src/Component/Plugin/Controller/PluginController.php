<?php
/**
 * Coded by fionera.
 */

namespace App\Component\Plugin\Controller;

use App\Component\Theme\ThemeInterface;
use App\Component\Theme\ThemeNotFoundException;
use App\Entity\Language;
use App\Entity\Website;
use App\Service\Language\LanguageProvider;
use App\Service\Theme\ThemeProvider;
use App\Service\WebsiteProvider\WebsiteProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class PluginController extends Controller
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