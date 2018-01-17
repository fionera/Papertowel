<?php

namespace App\EventListener;

use App\Service\Language\LanguageVariables;
use App\Service\Theme\ThemeVariables;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SetVariablesKernelRequestListener
{
    /**
     * @var ThemeVariables
     */
    private $themeVariables;
    /**
     * @var LanguageVariables
     */
    private $languageVariables;

    /**
     * KernelRequestListener constructor.
     * @param ThemeVariables $themeVariables
     * @param LanguageVariables $languageVariables
     */
    public function __construct(ThemeVariables $themeVariables, LanguageVariables $languageVariables)
    {
        $this->themeVariables = $themeVariables;
        $this->languageVariables = $languageVariables;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->themeVariables->setTemplatePath();
        $this->themeVariables->assignTemplateVariables();
        $this->languageVariables->assignLanguageVariables();
    }
}