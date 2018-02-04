<?php
/**
 * Coded by fionera.
 */


namespace Papertowel\Framework\Struct;


class ArrayList implements ListInterface
{
    protected $data = [];

    public function add($object, int $index = -1): void
    {
        if ($index === -1) {
            $this->data[] = $object;
        } else {
            $slice1 = \array_slice($this->data, 0, $index);
            $slice1[] = $object;

            $slice2 = \array_slice($this->data, $index);

            $this->data = array_merge($slice1, $slice2);
        }
    }

    public function addAll(array $array, int $index = -1): bool
    {
        $oldData = $this->data;

        if ($index === -1) {
            foreach ($array as $item) {
                $this->data[] = $item;
            }
        } else {
            $slice1 = \array_slice($this->data, 0, $index);

            foreach ($array as $item) {
                $slice1[] = $item;
            }

            $slice2 = \array_slice($this->data, $index);

            $this->data = array_merge($slice1, $slice2);
        }

        return $oldData !== $this->data;
    }

    public function get(int $index)
    {
        return $this->data[$index];
    }

    public function indexOf($object): int
    {
        foreach ($this->data as $index => $item) {
            if ($item === $object) {
                return $index;
            }
        }

        return -1;
    }

    public function lastIndexOf($object): int
    {
        $lastFound = -1;

        foreach ($this->data as $index => $item) {
            if ($item === $object) {
                $lastFound = $index;
            }
        }

        return $lastFound;
    }

    public function remove(int $index)
    {
        if (\count($this->data) < $index) {
            return null;
        }

        $object = $this->data[$index];

        unset($this->data[$index]);

        $this->data = array_merge($this->data, []); // Reindexing

        return $object;
    }

    public function set($object, int $index)
    {
        $previousObject = null;
        if (isset($this->data[$index])) {
            $previousObject = $this->data[$index];
        }

        $this->data[$index] = $object;

        return $previousObject;
    }

    public function getArray() : array
    {
        return $this->data;
    }
}