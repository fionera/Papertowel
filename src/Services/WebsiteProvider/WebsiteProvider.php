<?php

namespace App\Services\WebsiteProvider;

use App\Entity\Website;
use App\Services\Language\LanguageProvider;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RequestStack;

class WebsiteProvider
{
    /**
     * @var RequestStack $requestStack
     */
    protected $requestStack;

    /**
     * @var ManagerRegistry $doctrine
     */
    private $doctrine;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var LanguageProvider
     */
    private $languageProvider;

    /**
     * WebsiteProvider constructor.
     * @param LanguageProvider $languageProvider
     * @param RequestStack $requestStack
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     */
    public function __construct(LanguageProvider $languageProvider, RequestStack $requestStack, ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->languageProvider = $languageProvider;
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    /**
     * @return Website
     * @throws \LogicException
     */
    public function getWebsite(): Website
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            throw new \LogicException('CurrentRequest should not be null');
        }

        $requestDomain = $this->extractDomain($request->getHost());
        $requestSubDomain = $this->extractSubDomains($request->getHost());

        $website = $this->doctrine->getRepository('App:Website')->findWebsiteBySubAndDomain($requestSubDomain, $requestDomain);

        if ($website === null) {
            $website = $this->doctrine->getRepository('App:Website')->findWebsiteByDomain($requestDomain);
            $this->logger->debug('Using Domain');
        }

        if ($website === null) {
            $website = new Website('', 'Base', '', $this->languageProvider->getDefaultLanguage());
            $this->logger->error('Could not find Website: "' . $requestDomain . '"');
        }

        return $website;

    }

    /**
     * @param $domain string
     * @return string
     */
    private function extractDomain($domain): string
    {
        if (preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches)) {
            return $matches['domain'];
        }

        return $domain;
    }

    /**
     * @param $fullDomain string
     * @return string|null
     */
    private function extractSubDomains($fullDomain)
    {
        $domain = $this->extractDomain($fullDomain);

        return rtrim(strstr($fullDomain, $domain, true), '.');
    }
}