<?php

declare(strict_types=1);

namespace PostcodesIO\API\Contract;

use Closure;
use Countable;
use ArrayAccess;
use JsonSerializable;
use IteratorAggregate;

interface CollectionInterface extends
    ArrayAccess,
    Countable,
    IteratorAggregate,
    JsonSerializable
{
    /**
     * @return mixed
     */
    public function getFirst();

    /**
     * @return mixed
     */
    public function getLast();

    public function getArrayCopy() : array;

    public function isEmpty() : bool;

    public function map(Closure $func) : self;

    public function filter(Closure $p) : self;
}
