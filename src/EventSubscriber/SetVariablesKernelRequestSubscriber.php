<?php

namespace Papertowel\EventSubscriber;

use Papertowel\Component\Theme\ThemeInterface;
use Papertowel\Entity\Language;
use Papertowel\Entity\Website;
use Papertowel\Service\Language\LanguageProvider;
use Papertowel\Service\Theme\ThemeProvider;
use Papertowel\Service\Theme\ThemeVariables;
use Papertowel\Service\Website\WebsiteProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @throws \LogicException
     * @throws \Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException
     * @throws \Papertowel\Component\Theme\ThemeNotFoundException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->done !== true) {
            $this->website = $this->websiteProvider->getWebsite($event->getRequest());
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
     * @throws \Papertowel\Component\Theme\ThemeNotFoundException
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
     * @throws \Papertowel\Component\Theme\ThemeNotFoundException
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

        $themeVariables['javascripts'] = array_merge(...$themeVariables['javascripts']);
        $themeVariables['stylesheets'] = array_merge(...$themeVariables['stylesheets']);

        return $themeVariables;
    }


    public function setTemplatePath(): void
    {
        /** @var Twig_LoaderInterface $loader */
        $loader = $this->environment->getLoader();

        /** @var string[] $themePaths */
        $themePaths = $this->themeProvider->getAllThemeFoldersWithName();

        if ($loader instanceof FilesystemLoader) {
            foreach ($themePaths as $themeName => $path) {
                if ($themeName)
                    $loader->setPaths($path, $themeName);
            }

            $loader->setPaths($themePaths[$this->theme->getName()]);
//            else {
//                $paths = $loader->getPaths();
//                $paths[0] = $themePaths;
//                $loader->setPaths($paths);
//            }
        } else {
            throw new \LogicException('Twig Templateloader is not an Instance of FilesystemLoader');
        }
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
