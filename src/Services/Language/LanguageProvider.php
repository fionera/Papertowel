<?php
/**
 * Coded by fionera.
 */


namespace App\Services\Language;


use App\Entity\Language;
use App\Entity\Translation;
use App\Services\Session\SessionProvider;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class LanguageProvider
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var SessionProvider
     */
    private $sessionProvider;

    /**
     * LanguageProvider constructor.
     * @param Environment $environment
     * @param ManagerRegistry $managerRegistry
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     * @param SessionProvider $sessionProvider
     */
    public function __construct(Environment $environment, ManagerRegistry $managerRegistry, RequestStack $requestStack, LoggerInterface $logger, SessionProvider $sessionProvider)
    {
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $this->environment = $environment;
        $this->managerRegistry = $managerRegistry;
        $this->sessionProvider = $sessionProvider;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->managerRegistry->getRepository(Language::class)->findOneBy(['id' => 0]);
    }

    /**
     * @return Language
     */
    public function getCurrentLanguage(): Language
    {
        if ($this->sessionProvider->getSession()->has('selectedLanguage')) {
            $selectedLanguageId = $this->sessionProvider->getSession()->get('selectedLanguage');

            $language = $this->managerRegistry->getRepository(Language::class)->findOneBy(['id' => $selectedLanguageId]);
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

        $this->managerRegistry->getRepository(Translation::class)->getTranslationForLanguage($languageLanguage, $translationId);
    }
}