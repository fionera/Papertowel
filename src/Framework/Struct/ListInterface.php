<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Struct;

interface ListInterface
{
    public function add($object, int $index = -1): void;

    public function addAll(array $array, int $index = -1): bool;

    public function get(int $index);

    public function indexOf($object): int;

    public function lastIndexOf($object): int;

    public function remove(int $index);

    public function set($object, int $index);
}