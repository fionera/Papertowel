<?php

namespace App\Services\Theme;

use App\Components\Theme\CombinedTheme;
use App\Components\Theme\Theme;
use App\Components\Theme\ThemeInterface;
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
        /** @var ThemeInterface $theme */
        $theme = $this->themeProvider->getCombinedTheme($this->themeProvider->getThemeForCurrentRequest());

        $themeVariables = [
            'stylesheets' => $theme->getCss(),
            'javascripts' => $theme->getJavascript()
        ];

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

        /** @var string[] $themePaths */
        $themePaths = $this->themeProvider->getTemplatePaths();

        if ($loader instanceof FilesystemLoader) {
            if (\count($loader->getPaths()) === 1) {
                $loader->setPaths($themePaths);
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