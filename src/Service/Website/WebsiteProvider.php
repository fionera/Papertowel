<?php

namespace App\Service\Website;

use App\Entity\Website;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class WebsiteProvider
{
    /**
     * @var RegistryInterface $doctrine
     */
    private $doctrine;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * LanguageProvider constructor.
     * @param RegistryInterface $registry
     * @param LoggerInterface $logger
     */
    public function __construct(RegistryInterface $registry, LoggerInterface $logger)
    {
        $this->doctrine = $registry;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return null|Website
     * @throws SuspiciousOperationException
     */
    public function getWebsite(Request $request): ?Website
    {
        $requestDomain = $this->extractDomain($request->getHost());
        $requestSubDomain = $this->extractSubDomains($request->getHost());

        $website = $this->doctrine->getRepository('App:Website')->findWebsiteBySubAndDomain($requestSubDomain, $requestDomain);

        if ($website === null) {
            $website = $this->doctrine->getRepository('App:Website')->findWebsiteByDomain($requestDomain);
            $this->logger->debug('Using Domain');
        }

        if ($website === null) {
            $this->logger->debug('Could not find Website: "' . $requestDomain . '"');
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