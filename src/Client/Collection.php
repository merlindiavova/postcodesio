<?php

declare(strict_types=1);

namespace PostcodesIO\API\Client;

use Closure;
use Immutable\Bag;
use PostcodesIO\API\Contract\CollectionInterface;

/**
 * Immutable Collection class
 */
final class Collection extends Bag implements CollectionInterface
{
    /**
     * @return mixed
     */
    public function getFirst()
    {
        return reset($this->data);
    }

    /**
     * @return mixed
     */
    public function getLast()
    {
        return end($this->data);
    }

    public function getArrayCopy() : array
    {
        return $this->data;
    }

    public function isEmpty() : bool
    {
        return empty($this->data);
    }

    public function map(Closure $func) : CollectionInterface
    {
        return $this->createForm(array_map($func, $this->data));
    }

    public function filter(Closure $p) : CollectionInterface
    {
        return $this->createForm(
            array_filter($this->data, $p, ARRAY_FILTER_USE_BOTH)
        );
    }

    private function createForm(array $data) : CollectionInterface
    {
        $clone = clone $this;
        $clone->set($data);

        return $clone;
    }
}
