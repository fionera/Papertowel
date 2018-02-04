<?php

/**
 * Coded by fionera.
 */

namespace Papertowel\Service\Plugin;


use Papertowel\Component\Plugin\PluginInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PluginProvider
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $pluginBasePath;

    /**
     * @var PluginInterface[]|null
     */
    private $plugins = null;

    /** @var bool Already tried to load Plugins? */
    private $loaded = false;

    /**
     * LanguageProvider constructor.
     * @param LoggerInterface $logger
     * @param string $pluginBasePath
     */
    public function __construct(LoggerInterface $logger, string $pluginBasePath)
    {
        $this->logger = $logger;
        $this->pluginBasePath = $pluginBasePath;
    }

    /**
     * @return PluginInterface[]
     */
    public function getPluginList(): array
    {
        return $this->plugins ?? [];
    }

    /**
     * @return string[]
     */
    public function getPluginNames(): array
    {
        return array_map(function ($pluginFolder) {
            return basename($pluginFolder);
        }, $this->getAllPluginFolders());
    }

    /**
     * @param string $pluginName
     * @return PluginInterface|null
     */
    public function getPlugin(string $pluginName): ?PluginInterface
    {
        return $this->loaded === true ? $this->plugins[$pluginName] : null;
    }

    /**
     * @param string $pluginName
     */
    public function loadPlugin(string $pluginName): void
    {
        $this->plugins[$pluginName] = $this->loadPluginFromDisk($pluginName);
    }

    /**
     * @param array $pluginNames
     */
    public function loadPlugins(array $pluginNames = []): void
    {
        foreach ($pluginNames as $pluginName) {
            $this->loadPlugin($pluginName);
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
    private function loadPluginFromDisk(string $pluginName): ?PluginInterface
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