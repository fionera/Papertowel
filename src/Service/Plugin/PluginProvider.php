<?php

/**
 * Coded by fionera.
 */

namespace App\Service\Plugin;


use App\Component\Plugin\PluginInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PluginProvider
{
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
     * @var PluginInterface[]|null
     */
    private $plugins = null;

    /** @var bool Already tried to load Plugins? */
    private $booted = false;

    /**
     * LanguageProvider constructor.
     * @param LoggerInterface $logger
     * @param KernelInterface $kernel
     * @param string $pluginBasePath
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel, string $pluginBasePath)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->pluginBasePath = $pluginBasePath;
    }

    /**
     * @return PluginInterface[]
     */
    public function getPluginList(): array
    {
        $this->bootPluginLoader();

        return $this->plugins ?? [];
    }

    /**
     * @param string $pluginName
     * @return PluginInterface|null
     */
    public function getPlugin(string $pluginName): ?PluginInterface
    {
        $this->bootPluginLoader();

        return $this->plugins[$pluginName];
    }

    private function bootPluginLoader(): void
    {
        if (!$this->booted) {
            $this->loadAllPlugins();

            $this->booted = true;
        }
    }

    /**
     * @throws \LogicException
     */
    private function loadAllPlugins(): void
    {
        foreach ($this->getAllPluginFolders() as $pluginFolder) {
            $pluginName = basename($pluginFolder);
            $this->plugins[$pluginName] = $this->loadPlugin($pluginName);
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
        $classPath = $this->pluginBasePath . '/' . $pluginName . '/' . $pluginName . '.php';

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