<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Translation;

use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Translation\Translation;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @var SessionInterface
     */
    private $session;

    /**
     * LanguageProvider constructor.
     * @param RegistryInterface $registry
     * @param LoggerInterface $logger
     * @param SessionInterface $session
     */
    public function __construct(RegistryInterface $registry, LoggerInterface $logger, SessionInterface $session)
    {
        $this->logger = $logger;
        $this->registry = $registry;
        $this->session = $session;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->registry->getRepository(Language::class)->findOneBy(['id' => 1]);
    }

    /**
     * @return Language
     */
    public function getCurrentLanguage(): Language
    {
        if ($this->session->has('selectedLanguage')) {
            $selectedLanguageId = $this->session->get('selectedLanguage');

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
