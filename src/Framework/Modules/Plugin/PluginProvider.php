<?php

/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin;

use Papertowel\Framework\Modules\Plugin\Struct\PluginInterface;
use Zend\Code\Reflection\ClassReflection;

class PluginProvider
{
    /**
     * @var string
     */
    private $pluginBasePath;

    /**
     * @var Psr4ClassLoader
     */
    private $classLoader;

    /**
     * @var PluginInterface[]|null
     */
    private static $plugins;

    /**
     * LanguageProvider constructor.
     * @param string $pluginBasePath
     */
    public function __construct(string $pluginBasePath)
    {
        $this->pluginBasePath = $pluginBasePath;
        $this->classLoader = new Psr4ClassLoader();
        $this->classLoader->register();
    }

    /**
     * @return PluginInterface[]
     */
    public function getPluginList(): array
    {
        return self::$plugins ?? [];
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
        return self::$plugins[$pluginName] ?? null;
    }

    /**
     * @param string $pluginName
     */
    public function loadPlugin(string $pluginName): void
    {
        self::$plugins[$pluginName] = $this->loadPluginFromDisk($pluginName);
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
        $pluginPath = $this->pluginBasePath . '/' . $pluginName;
        $classPath = $pluginPath . '/' . $pluginName . '.php';

        if (!file_exists($classPath)) {
            throw new \LogicException('Could not find ' . $pluginName . '.php for Plugin');
        }

        require_once $classPath;
        $className = $pluginName . '\\' . $pluginName;

        $class = new $className;

        if (!($class instanceof PluginInterface)) {
            throw new \LogicException('Plugin must Implement PluginInterface: "' . $pluginName . '"');
        }

        $this->classLoader->addNamespace($pluginName, $pluginPath);

        return $class;
    }
}
