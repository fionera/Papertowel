<?php

/**
 * Coded by fionera.
 */

namespace App\Service\Plugin;


use App\Component\Plugin\Plugin;
use App\Component\Plugin\PluginInterface;
use App\Service\WebsiteProvider\WebsiteProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PluginLoader
{
    /**
     * @var WebsiteProvider
     */
    private $websiteProvider;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var string
     */
    private $pluginBasePath;

    /**
     * @var PluginInterface
     */
    private $plugins = [];

    /**
     * ThemeProvider constructor.
     * @param WebsiteProvider $websiteProvider
     * @param LoggerInterface $logger
     * @param KernelInterface $kernel
     * @param string $pluginBasePath
     */
    public function __construct(WebsiteProvider $websiteProvider, LoggerInterface $logger, KernelInterface $kernel, string $pluginBasePath)
    {
        $this->websiteProvider = $websiteProvider;
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->pluginBasePath = $pluginBasePath;
    }

    /**
     * @throws \LogicException
     */
    private function loadAllPlugins(): void
    {
        foreach ($this->getAllPluginFolders() as $pluginFolder) {
            $this->plugins[basename($pluginFolder)] = $this->loadPlugin($pluginFolder);
        }
    }

    /**
     * @return array
     */
    private function getAllPluginFolders(): array
    {
        return glob($this->pluginBasePath . '/*', GLOB_ONLYDIR);
    }

    /**
     * @param string $pluginName
     * @return PluginInterface|null
     */
    private function loadPlugin(string $pluginName): ?PluginInterface
    {
        $classPath = $this->kernel->getRootDir() . '/../plugins/' . $pluginName . '/' . $pluginName . '.php';

        if (!file_exists($classPath)) {
            $this->logger->error('Could not find ' . $pluginName . '.php for Plugin');
        }

        require_once $classPath;
        $className = 'plugins\\' . $pluginName . '\\' . $pluginName;

        $class = new $className;

        if (!($class instanceof PluginInterface)) {
            $this->logger->error('Plugin must Implement PluginInterface: "' . $pluginName . '"');
            throw new \LogicException('');
        }

        return $class;
    }
}