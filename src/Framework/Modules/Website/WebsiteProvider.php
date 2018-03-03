<?php

namespace Papertowel\Framework\Modules\Website;

use Papertowel\Framework\Entity\Website\Website;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Request;

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
     * @var ContainerInterface
     */
    private $container;

    /**
     * LanguageProvider constructor.
     * @param ContainerInterface $container
     * @param RegistryInterface $registry
     * @param LoggerInterface $logger
     */
    public function __construct(ContainerInterface $container, RegistryInterface $registry, LoggerInterface $logger)
    {
        $this->doctrine = $registry;
        $this->logger = $logger;
        $this->container = $container;
    }

    /**
     * @param string $host
     * @return null|Website
     */
    public function getWebsiteByHost(string $host): ?Website
    {
        $requestDomain = $this->extractDomain($host);
        $requestSubDomain = $this->extractSubDomains($host);

        $website = $this->doctrine->getRepository(Website::class)->findWebsiteBySubAndDomain($requestSubDomain, $requestDomain);

        if ($website === null) {
            $website = $this->doctrine->getRepository(Website::class)->findWebsiteByDomain($requestDomain);
            $this->logger->debug('Using Domain');
        }

        if ($website === null) {
            $this->logger->debug('Could not find Website: "' . $requestDomain . '"');
        }

        return $website;
    }

    /**
     * @param Request $request
     * @return null|Website
     * @throws SuspiciousOperationException
     */
    public function getWebsiteByRequest(Request $request): ?Website
    {
        return $this->getWebsiteByHost($request->getHost());
    }

    public function getCurrentWebsite(): ?Website
    {
        $domain = null;
        if ($this->container->has('request_stack')) {
            $request = $this->container->get('request_stack')->getMasterRequest();

            if ($request !== null) {
             $domain = $request->getHost();
            }
        }

        if ($domain === null) {
            $input = new ArgvInput();

            $domain = $input->getParameterOption(['--domain', '-d'], null);
        }

        if ($domain === null) {
            return null;
        }

        return $this->getWebsiteByHost($domain);
    }

    /**
     * @param string $domain
     * @return string
     */
    private function extractDomain(string $domain): string
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
    private function extractSubDomains(string $fullDomain)
    {
        $domain = $this->extractDomain($fullDomain);

        return rtrim(strstr($fullDomain, $domain, true), '.');
    }
}
