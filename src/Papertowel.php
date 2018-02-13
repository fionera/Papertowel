<?php

namespace Papertowel;

use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Theme\Struct\ThemeInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class Papertowel
{

    /** @var Papertowel */
    private static $instance;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Papertowel constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return Papertowel
     */
    public static function getInstance(): Papertowel
    {
        return self::$instance;
    }

    /**
     * @param Papertowel $instance
     */
    public static function setInstance(Papertowel $instance): void
    {
        self::$instance = $instance;
    }

    /**
     * @return RegistryInterface
     */
    public function Doctrine(): RegistryInterface
    {
        return $this->container->get('doctrine');
    }

    /**
     * @return Environment
     */
    public function Twig(): Environment
    {
        return $this->container->get('twig');
    }

    /**
     * @return Website
     */
    public function Website(): Website
    {
        return $this->container->get('website');
    }

    /**
     * @return ThemeInterface
     */
    public function Theme(): ThemeInterface
    {
        return $this->container->get('theme');
    }
}

function Papertowel($instance = null): Papertowel
{
    if ($instance !== null) {
        Papertowel::setInstance($instance);
    }

    return Papertowel::getInstance();
}
