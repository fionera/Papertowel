<?php

namespace Papertowel\EventSubscriber;

use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Theme\Struct\ThemeInterface;
use Papertowel\Framework\Modules\Theme\ThemeProvider;
use Papertowel\Framework\Modules\Theme\Twig\Loader\ThemeInheritanceLoader;
use Papertowel\Framework\Modules\Translation\LanguageProvider;
use Papertowel\Framework\Modules\Website\WebsiteProvider;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig_LoaderInterface;

class SetVariablesKernelRequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var WebsiteProvider
     */
    private $websiteProvider;
    /**
     * @var LanguageProvider
     */
    private $languageProvider;
    /**
     * @var ThemeProvider
     */
    private $themeProvider;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var Website
     */
    private $website;

    /**
     * @var ThemeInterface
     */
    private $theme;

    private $done = false;

    /**
     * KernelRequestListener constructor.
     * @param WebsiteProvider $websiteProvider
     * @param LanguageProvider $languageProvider
     * @param ThemeProvider $themeProvider
     * @param Environment $environment
     * @param RegistryInterface $registry
     */
    public function __construct(WebsiteProvider $websiteProvider, LanguageProvider $languageProvider, ThemeProvider $themeProvider, Environment $environment, RegistryInterface $registry)
    {
        $this->websiteProvider = $websiteProvider;
        $this->languageProvider = $languageProvider;
        $this->themeProvider = $themeProvider;
        $this->environment = $environment;
        $this->registry = $registry;
    }

    /**
     * @param GetResponseEvent $event
     * @throws \Papertowel\Framework\Modules\Theme\Exception\ThemeNotFoundException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->done !== true) {
            $this->website = $this->websiteProvider->getWebsiteByRequest($event->getRequest());
            $this->theme = $this->themeProvider->getThemeByName($this->website->getThemeName());

            $this->setTemplatePath();
            $this->assignTemplateVariables();
            $this->assignLanguageVariables();

            $this->done = true;
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => ['onKernelRequest', 8001],
        ];
    }

    /**
     * @return void
     * @throws \LogicException
     * @throws \Papertowel\Framework\Modules\Theme\Exception\ThemeNotFoundException
     */
    public function assignTemplateVariables(): void
    {
        $this->environment->addGlobal('template', $this->getTemplateVariables());
    }

    /**
     * @return void
     */
    public function assignLanguageVariables(): void
    {
        $this->environment->addGlobal('translation', $this->getLanguagesForWebsite());
    }

    /**
     * @return array
     * @throws \LogicException
     * @throws \Papertowel\Framework\Modules\Theme\Exception\ThemeNotFoundException
     */
    public function getTemplateVariables(): array
    {
        /** @var ThemeInterface[] $theme */
        $themes = array_reverse($this->themeProvider->getDependencyThemes($this->theme));

        $themeVariables = [
            'stylesheets' => [],
            'javascripts' => []
        ];

        /** @var ThemeInterface $theme */
        foreach ($themes as $theme) {
            $themeVariables['javascripts'][] = $theme->getJavascript();
            $themeVariables['stylesheets'][] = $theme->getCss();
        }

        if (count($themeVariables['javascripts']) > 1) {
            $themeVariables['javascripts'] = array_merge(...$themeVariables['javascripts']);
            $themeVariables['stylesheets'] = array_merge(...$themeVariables['stylesheets']);
        }


        return $themeVariables;
    }


    public function setTemplatePath(): void
    {
        /** @var Twig_LoaderInterface $loader */
        $loader = $this->environment->getLoader();

        /** @var string[] $themePaths */
        $themePaths = $this->themeProvider->getAllThemeFoldersWithName();

        $newLoader = new ThemeInheritanceLoader($this->themeProvider->getThemeByName($this->theme->getDependency()));

        if ($loader instanceof FilesystemLoader) {
            foreach ($loader->getNamespaces() as $namespace) {
                $newLoader->setPaths($loader->getPaths($namespace), $namespace);
            }
        } else {
            throw new \LogicException('Twig Templateloader is not an Instance of FilesystemLoader');
        }

        foreach ($themePaths as $themeName => $path) {
            $newLoader->addPath($path, $themeName);
        }

        $newLoader->addPath($themePaths[$this->theme->getName()]);
        $this->environment->setLoader($newLoader);
    }

    /**
     * @return array
     */
    public function getLanguagesForWebsite(): array
    {
        $supportedLanguages = $this->website->getSupportedLanguages();

        $languageArray = ['selectedLanguage' => '', 'availableLanguages' => []];
        foreach ($supportedLanguages as $supportedLanguage) {
            $languageArray['availableLanguages'][$supportedLanguage->getLanguageString()] = $supportedLanguage->getName();
        }

        return $languageArray;
    }
}