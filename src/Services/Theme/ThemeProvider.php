<?php

namespace App\Services\Theme;

use App\Components\Theme\CombinedTheme;
use App\Components\Theme\Theme;
use App\Components\Theme\ThemeInterface;
use App\Services\WebsiteProvider\WebsiteProvider;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\KernelInterface;

class ThemeProvider
{
    /**
     * @var WebsiteProvider
     */
    private $websiteProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var string
     */
    private $templateBasePath;


    /**
     * ThemeProvider constructor.
     * @param WebsiteProvider $websiteProvider
     * @param LoggerInterface $logger
     * @param KernelInterface $kernel
     * @param string $templateBasePath
     */
    public function __construct(WebsiteProvider $websiteProvider, LoggerInterface $logger, KernelInterface $kernel, string $templateBasePath)
    {
        $this->websiteProvider = $websiteProvider;
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->templateBasePath = $templateBasePath;
    }

    public function getTemplatePaths(): array
    {
        $currentTheme = $this->getThemeForCurrentRequest()->getName();

        $combinedTheme = $this->getCombinedTheme($currentTheme);

        $templatePaths = [];
        foreach (explode(';', $combinedTheme->getName()) as $name) {
            $templatePaths[] = $this->getTemplatePath($name);
        }

        return array_reverse($templatePaths);
    }

    /**
     * @param string|ThemeInterface $theme
     * @return string
     */
    public function getTemplatePath($theme): string
    {
        $themeName = $theme;
        if ($theme instanceof ThemeInterface) {
            $themeName = $theme->getName();
        }

        return $this->templateBasePath . '/' . $themeName;
    }

    /**
     * @param ThemeInterface|string $theme
     * @return ThemeInterface
     * @throws \LogicException
     */
    public function getCombinedTheme($theme) : ThemeInterface
    {
        return new CombinedTheme($theme, $this);
    }

    /**
     * @return ThemeInterface
     * @throws \LogicException
     */
    public function getThemeForCurrentRequest(): ThemeInterface
    {
        $website = $this->websiteProvider->getWebsite();

        return $this->getThemeByName($website->getThemeName());
    }

    /**
     * @return null|ThemeInterface
     * @throws \LogicException
     */
    public function getThemeByName(string $themeName): ?ThemeInterface
    {
        $classPath = $this->kernel->getRootDir() . '/../templates/' . $themeName . '/Theme.php';
        //$classPath = '../../../templates/Base/Theme.php';

        if (!file_exists($classPath)) {
            $this->logger->error('Could not find Theme.php for Theme: "' . $themeName . '"');
            throw new \LogicException('');
        }

        require_once $classPath;
        $className = 'templates\\' . $themeName . '\\Theme';

        $class = new $className;

        if (!($class instanceof ThemeInterface)) {
            $this->logger->error('Theme must Implement ThemeInterface: "' . $themeName . '"');
            throw new \LogicException('');
        }

        return $class;
    }
}