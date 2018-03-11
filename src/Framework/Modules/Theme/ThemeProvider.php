<?php

namespace Papertowel\Framework\Modules\Theme;

use Papertowel\Framework\Modules\Theme\Exception\ThemeNotFoundException;
use Papertowel\Framework\Modules\Theme\Struct\ThemeInterface;
use Papertowel\Framework\Modules\Website\Struct\WebsiteInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ThemeProvider
{
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
    private $themeBasePath;

    /**
     * A cache for all Themes
     * @var ThemeInterface[]
     */
    private static $themes = [];

    private $booted;

    /**
     * LanguageProvider constructor.
     * @param LoggerInterface $logger
     * @param KernelInterface $kernel
     * @param string $themeBasePath
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel, string $themeBasePath)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->themeBasePath = $themeBasePath;
    }

    /**
     * @param ThemeInterface $theme
     * @return string[]
     * @throws \LogicException
     * @throws ThemeNotFoundException
     */
    public function getDependencyNames(ThemeInterface $theme): array
    {
        $dependencies = [];

        $currentTheme = $theme;
        while (!empty($currentTheme->getDependency())) {
            $dependencies[] = $currentTheme->getDependency();
            $currentTheme = $this->getThemeByName($currentTheme->getDependency());
        }

        return $dependencies;
    }

    /**
     * @param ThemeInterface $theme
     * @return ThemeInterface[]
     * @throws \LogicException
     * @throws ThemeNotFoundException
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
     * @param string $themeName
     * @return ThemeInterface
     * @throws ThemeNotFoundException
     */
    public function getThemeByName(string $themeName): ThemeInterface
    {
        if (!$this->booted) {
            $this->loadAllThemes();
        }

        if (!array_key_exists($themeName, self::$themes)) {
            throw new ThemeNotFoundException('Could not found Theme "' . $themeName . '"');
        }

        return self::$themes[$themeName];
    }

    /**
     * @param WebsiteInterface $website
     * @return ThemeInterface
     * @throws ThemeNotFoundException
     */
    public function getCurrentTheme(WebsiteInterface $website): ThemeInterface
    {
        return $this->getThemeByName($website->getThemeName());
    }

    /**
     * @return string[]
     */
    public function getThemeNames(): array
    {
        return array_keys(self::$themes);
    }

    private function loadAllThemes(): void
    {
        foreach ($this->getAllThemeFoldersWithName() as $themeName => $themeFolder) {
            self::$themes[$themeName] = $this->loadTheme($themeName);
        }

        $this->booted = true;
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
        return glob($this->themeBasePath . '/*', GLOB_ONLYDIR);
    }

    /**
     * @return null|ThemeInterface
     * @throws \LogicException
     */
    private function loadTheme(string $themeName): ?ThemeInterface
    {
        $classPath = $this->themeBasePath . '/' . $themeName . '/Theme.php';

        if (!file_exists($classPath)) {
            $this->logger->error('Could not find Theme.php for Theme: "' . $themeName . '"');
            throw new \LogicException('Could not find Theme.php for Theme: "' . $themeName . '"');
        }

        require_once $classPath;
        $className = $themeName . '\\Theme';

        $class = new $className;

        if (!($class instanceof ThemeInterface)) {
            $this->logger->error('Theme must Implement ThemeInterface: "' . $themeName . '"');
            throw new \LogicException('');
        }

        return $class;
    }
}
