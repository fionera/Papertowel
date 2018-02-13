<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin\Struct\Controller;

use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Theme\Struct\ThemeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class PluginController extends Controller
{
    /**
     * @var Website
     */
    private $website;

    /**
     * @var ThemeInterface
     */
    private $theme;

    /**
     * @var Language
     */
    private $language;

    /**
     * PluginController constructor.
     * @param Website $website
     * @param ThemeInterface $theme
     * @param Language $language
     */
    public function __construct(Website $website, ThemeInterface $theme, Language $language)
    {
        $this->website = $website;
        $this->theme = $theme;
        $this->language = $language;
    }

    /**
     * @return Website
     */
    public function getWebsite(): Website
    {
        return $this->website;
    }

    /**
     * @return ThemeInterface
     */
    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }

    /**
     * @return Language
     */
    public function getCurrentLanguage(): Language
    {
        return $this->language;
    }
}
