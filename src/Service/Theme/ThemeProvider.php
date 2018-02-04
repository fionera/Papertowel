<?php

namespace Papertowel\Service\Theme;

use Papertowel\Component\Theme\ThemeInterface;
use Papertowel\Component\Theme\ThemeNotFoundException;
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
    private $themes = [];

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
        $className = 'templates\\' . $themeName . '\\Theme';

        $class = new $className;

        if (!($class instanceof ThemeInterface)) {
            $this->logger->error('Theme must Implement ThemeInterface: "' . $themeName . '"');
            throw new \LogicException('');
        }

        return $class;
    }
}