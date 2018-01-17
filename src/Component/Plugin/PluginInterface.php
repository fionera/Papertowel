<?php
/**
 * Coded by fionera.
 */

namespace App\Component\Plugin;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface PluginInterface
{
    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return string
     */
    public function getAuthor() : string;

    /**
     * @return float
     */
    public function getVersion() : double;

    /**
     * @param ContainerInterface $container
     */
    public function onInstall(ContainerInterface $container) : void;

    /**
     * @param ContainerInterface $container
     */
    public function onEnable(ContainerInterface $container) : void;

    /**
     * @param ContainerInterface $container
     */
    public function onDisable(ContainerInterface $container) : void;

    /**
     * @param ContainerInterface $container
     */
    public function onUninstall(ContainerInterface $container) : void;
}