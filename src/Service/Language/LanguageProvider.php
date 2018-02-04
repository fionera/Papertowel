<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Service\Language;

use Papertowel\Entity\Language;
use Papertowel\Entity\Translation;
use Papertowel\Service\Session\SessionProvider as SessionProvider;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class LanguageProvider
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RegistryInterface
     */
    private $registry;
    /**
     * @var SessionProvider
     */
    private $sessionProvider;

    /**
     * LanguageProvider constructor.
     * @param RegistryInterface $registry
     * @param LoggerInterface $logger
     * @param SessionProvider $sessionProvider
     */
    public function __construct(RegistryInterface $registry, LoggerInterface $logger, SessionProvider $sessionProvider)
    {
        $this->logger = $logger;
        $this->registry = $registry;
        $this->sessionProvider = $sessionProvider;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->registry->getRepository(Language::class)->findOneBy(['id' => 0]);
    }

    /**
     * @return Language
     */
    public function getCurrentLanguage(): Language
    {
        if ($this->sessionProvider->getSession()->has('selectedLanguage')) {
            $selectedLanguageId = $this->sessionProvider->getSession()->get('selectedLanguage');

            $language = $this->registry->getRepository(Language::class)->findOneBy(['id' => $selectedLanguageId]);
            if ($language !== null) {
                return $language;
            }
        }

        return $this->getDefaultLanguage();
    }

    /**
     * @param Language $language The Language from which you want the Name
     * @param Language|null $languageLanguage The Language in which you want the Name
     */
    private function getNameForLanguage(Language $language, Language $languageLanguage = null)
    {
        $translationId = $language->getName()->getTranslationId();
        if ($languageLanguage === null) {
            $languageLanguage = $this->getCurrentLanguage();
        }

        $this->registry->getRepository(Translation::class)->getTranslationForLanguage($languageLanguage, $translationId);
    }
}