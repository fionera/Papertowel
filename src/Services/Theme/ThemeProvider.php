<?php

namespace App\Services\Theme;

use App\Components\Theme\CombinedTheme;
use App\Components\Theme\ThemeInterface;
use App\Services\WebsiteProvider\WebsiteProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use templates\Base\Theme;

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

    /**
     * @param ThemeInterface $theme
     * @return string[]
     * @throws \LogicException
     */
    public function getDependencyNames(ThemeInterface $theme): array
    {
        $dependencies = [];

        $dependencies[] = $theme->getDependencies();

        foreach ($theme->getDependencies() as $dependency) {
            $dependencies[] = $this->getDependencyNames($this->getThemeByName($dependency));
        }

        return array_unique(array_merge(...$dependencies));
    }

    /**
     * @param ThemeInterface $theme
     * @return ThemeInterface[]
     * @throws \LogicException
     */
    public function getDependencyThemes(ThemeInterface $theme): array
    {
        $themes = [];
        foreach ($this->getDependencyNames($theme) as $dependencyName) {
            $themes[] = $this->getThemeByName($dependencyName);
        }

        return $themes;
    }

    /**
     * @param ThemeInterface $themeInterface
     * @return string[]
     * @throws \LogicException
     */
    public function getDependencyNamespaces(ThemeInterface $themeInterface) : array
    {
        $namespaces = [];
        foreach ($this->getDependencyThemes($themeInterface) as $theme) {
            $namespaces[$theme->getName()] = $this->getTemplatePath($theme);
        }

        return $namespaces;
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