<?php
/**
 * Coded by fionera.
 */

namespace App\Service\Language;

use App\Entity\Language;
use App\Service\Website\WebsiteProvider;
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
     * @param LanguageProvider $websiteProvider
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



}