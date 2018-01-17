<?php

namespace App\Service\Theme;

use App\Component\Theme\ThemeInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig_LoaderInterface;

class ThemeVariables
{
    /**
     * @var ThemeProvider
     */
    private $themeProvider;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * ThemeBundle constructor.
     * @param ThemeProvider $themeProvider
     * @param Environment $environment
     */
    public function __construct(ThemeProvider $themeProvider, Environment $environment)
    {
        $this->themeProvider = $themeProvider;
        $this->environment = $environment;
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getTemplateVariables(): array
    {
        /** @var ThemeInterface[] $theme */
        $themes = array_reverse($this->themeProvider->getDependencyThemes($this->themeProvider->getThemeForCurrentRequest()));

        $themeVariables = [
            'stylesheets' => [],
            'javascripts' => []
        ];

        /** @var ThemeInterface $theme */
        foreach ($themes as $theme) {
            $themeVariables['javascripts'][] = $theme->getJavascript();
            $themeVariables['stylesheets'][] = $theme->getCss();
        }

        $themeVariables['javascripts'] = array_merge(...$themeVariables['javascripts']);
        $themeVariables['stylesheets'] = array_merge(...$themeVariables['stylesheets']);

        return $themeVariables;
    }

    public function assignTemplateVariables(): void
    {
        $this->environment->addGlobal('template', $this->getTemplateVariables());
    }

    public function setTemplatePath(): void
    {
        /** @var Twig_LoaderInterface $loader */
        $loader = $this->environment->getLoader();

        $currentTheme = $this->themeProvider->getThemeForCurrentRequest()->getName();

        /** @var string[] $themePaths */
        $themePaths = $this->themeProvider->getAllThemeFoldersWithName();

        if ($loader instanceof FilesystemLoader) {
            if (\count($loader->getPaths()) === 1) {
                foreach ($themePaths as $themeName => $path) {
                    if ($themeName)
                    $loader->setPaths($path, $themeName);
                }

                $loader->setPaths($themePaths[$currentTheme]);
            }
//            else {
//                $paths = $loader->getPaths();
//                $paths[0] = $themePaths;
//                $loader->setPaths($paths);
//            }
        } else {
            throw new \LogicException('Twig Templateloader is not an Instance of FilesystemLoader');
        }
    }
}