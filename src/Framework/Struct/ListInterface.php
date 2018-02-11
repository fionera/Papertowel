<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Struct;

/**
 * Interface ListInterface
 * @package Papertowel\Framework\Struct
 */
interface ListInterface
{
    /**
     * @param $object
     * @param int $index
     */
    public function add($object, int $index = -1): void;

    /**
     * @param array $array
     * @param int $index
     * @return bool
     */
    public function addAll(array $array, int $index = -1): bool;

    /**
     * @param int $index
     * @return mixed|null
     */
    public function get(int $index);

    /**
     * @param $object
     * @return bool
     */
    public function has($object): bool;

    /**
     * @param $object
     * @return int
     */
    public function indexOf($object): int;

    /**
     * @param $object
     * @return int
     */
    public function lastIndexOf($object): int;

    /**
     * @param int $index
     * @return mixed
     */
    public function remove(int $index);

    /**
     * @param $object
     * @param int $index
     * @return mixed
     */
    public function set($object, int $index);
}