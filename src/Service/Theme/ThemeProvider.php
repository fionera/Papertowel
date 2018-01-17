<?php

namespace App\Service\Theme;

use App\Component\Theme\ThemeInterface;
use App\Component\Theme\ThemeNotFoundException;
use App\Service\WebsiteProvider\WebsiteProvider;
use Psr\Log\LoggerInterface;
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


    private $themes = [];


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
     * @param string|ThemeInterface $theme
     * @return string
     * @throws ThemeNotFoundException
     */
    public function getTemplatePath($theme): string
    {

        $themeName = $theme;
        if ($theme instanceof ThemeInterface) {
            $themeName = $theme->getName();
        }

        if (!isset($this->getAllThemeFoldersWithName()[$themeName])) {
            throw new ThemeNotFoundException('Could not found Theme "' . $themeName . '"');
        }

        return $this->getAllThemeFoldersWithName()[$themeName];
    }

    /**
     * @return ThemeInterface
     * @throws \App\Component\Theme\ThemeNotFoundException
     * @throws \LogicException
     */
    public function getThemeForCurrentRequest(): ThemeInterface
    {
        $website = $this->websiteProvider->getWebsite();

        return $this->getThemeByName($website->getThemeName());
    }

    /**
     * @param string $themeName
     * @return ThemeInterface
     * @throws ThemeNotFoundException
     */
    public function getThemeByName(string $themeName): ThemeInterface
    {
        if (\count($this->themes) === 0) {
            $this->loadAllThemes();
        }

        if (!array_key_exists($themeName, $this->themes)) {
            throw new ThemeNotFoundException('Could not found Theme "' . $themeName . '"');
        }

        return $this->themes[$themeName];
    }

    /**
     * @return string[]
     */
    public function getThemeNames(): array
    {
        return array_keys($this->themes);
    }

    private function loadAllThemes(): void
    {
        foreach ($this->getAllThemeFoldersWithName() as $themeName => $themeFolder) {
            $this->themes[$themeName] = $this->loadTheme($themeName);
        }
    }

    public function getAllThemeFoldersWithName(): array
    {
        return array_merge(...array_map(function ($themeFolder) {
            return [basename($themeFolder) => $themeFolder];
        }, $this->getAllThemeFolders()));
    }

    /**
     * @return string[]
     */
    private function getAllThemeFolders(): array
    {
        return glob($this->templateBasePath . '/*', GLOB_ONLYDIR);
    }

    /**
     * @return null|ThemeInterface
     * @throws \LogicException
     */
    private function loadTheme(string $themeName): ?ThemeInterface
    {
        $classPath = $this->kernel->getRootDir() . '/../templates/' . $themeName . '/Theme.php';
        //$classPath = '../../../templates/Base/Theme.php';

        if (!file_exists($classPath)) {
            $this->logger->error('Could not find Theme.php for Theme: "' . $themeName . '"');
            throw new \LogicException('Could not find Theme.php for Theme: "' . $themeName . '"');
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