<?php

declare(strict_types=1);

namespace PostcodesIO\API\Contract;

use PostcodesIO\API\Contract\CollectionInterface;

interface ResponseInterface
{
    public function getData() : array;

    public function getResult() : CollectionInterface;

    public function hasValidResult() : bool;

    public function hasErrors() : bool;

    public function isSuccessful() : bool;
}
