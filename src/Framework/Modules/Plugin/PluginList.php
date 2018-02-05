<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin;

use Papertowel\Framework\Modules\Plugin\Entity\Plugin;
use Papertowel\Framework\Modules\Plugin\Struct\PluginInterface;
use Papertowel\Framework\Modules\Website\Entity\Website;
use Papertowel\Framework\Struct\ListInterface;

class PluginList
{
    /**
     * @var PluginInterface[]
     */
    private $plugins;

    /**
     * @param PluginInterface $plugin
     */
    public function add(PluginInterface $plugin): void
    {
        $className = get_class($plugin);
        $className = substr($className, 0, strpos($className, '\\'));

        if ($this->has($className)) {
            return;
        }

        $this->plugins[$className] = $plugin;
    }

    /**
     * @param PluginInterface[] $array
     * @return bool
     */
    public function addAll(array $array): bool
    {
        foreach ($array as $item) {
            $this->add($item);
        }

        return true;
    }

    /**
     * @param string $pluginName
     * @return PluginInterface|null
     */
    public function get(string $pluginName): ?PluginInterface
    {
        return $this->has($pluginName) ? $this->plugins[$pluginName] : null;
    }

    /**
     * @param $pluginName
     * @return bool
     */
    public function has($pluginName): bool
    {
        return array_key_exists($pluginName, $this->plugins);
    }

    /**
     * @return PluginInterface[]
     */
    public function getActivePlugins()
    {
        return array_filter($this->plugins, function (PluginInterface $plugin) {
            return $plugin->isEnabled();
        });
    }
}