<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin;

use Papertowel\Framework\Modules\Plugin\Struct\PluginInterface;
use Papertowel\Framework\Struct\ListInterface;

class PluginList implements ListInterface
{

    public function add($object, int $index = -1): void
    {
    }

    public function addAll(array $array, int $index = -1): bool
    {
        // TODO: Implement addAll() method.
    }

    public function get(int $index)
    {
        // TODO: Implement get() method.
    }

    public function has($object): bool
    {

    }

    public function indexOf($object): int
    {
        // TODO: Implement indexOf() method.
    }

    public function lastIndexOf($object): int
    {
        // TODO: Implement lastIndexOf() method.
    }

    public function remove(int $index)
    {
        // TODO: Implement remove() method.
    }

    public function set($object, int $index)
    {
        // TODO: Implement set() method.
    }
}