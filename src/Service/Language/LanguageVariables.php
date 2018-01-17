<?php
/**
 * Coded by fionera.
 */

namespace App\Service\Language;

use App\Entity\Language;
use App\Service\WebsiteProvider\WebsiteProvider;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Twig\Environment;

class LanguageVariables
{
    /**
     * @var WebsiteProvider
     */
    private $websiteProvider;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var LanguageProvider
     */
    private $languageProvider;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * LanguageVariables constructor.
     * @param WebsiteProvider $websiteProvider
     * @param LanguageProvider $languageProvider
     * @param Environment $environment
     * @param ContainerInterface $container
     */
    public function __construct(WebsiteProvider $websiteProvider, LanguageProvider $languageProvider, Environment $environment, ContainerInterface $container)
    {
        $this->websiteProvider = $websiteProvider;
        $this->environment = $environment;
        $this->languageProvider = $languageProvider;
        $this->container = $container;
    }


    public function assignLanguageVariables(): void
    {
        $this->environment->addGlobal('translation', $this->getLanguagesForWebsite());
    }


    public function getLanguagesForWebsite()
    {
        /** @var string $supportedLanguages */
        $supportedLanguages = $this->websiteProvider->getWebsite()->getSupportedLanguages();

        //TODO: Replace with fancy Array Code
        {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $languageIDs = explode(';', $supportedLanguages[0]);

            /** @var Language[] $languages */
            $languages = $em->getRepository(Language::class)->findAll();

            /** @var Language[] $supportedLanguages */
            $supportedLanguages = [];
            /** @var Language $language */
            foreach ($languages as $language) {
                if (in_array($language->getId(), $languageIDs)) {
                    $supportedLanguages[] = $language;
                }
            }
        }

        $languageArray = ['selectedLanguage' => '', 'availableLanguages' => []];
        foreach ($supportedLanguages as $supportedLanguage) {
            $languageArray['availableLanguages'][$supportedLanguage->getLanguageString()] = $supportedLanguage->getName();
        }

        return $languageArray;
    }


}