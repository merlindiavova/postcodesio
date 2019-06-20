<?php

declare(strict_types=1);

namespace PostcodesIO\API\Client;

use PostcodesIO\API\Postcode\Data;
use PostcodesIO\API\Contract\ResponseInterface;
use PostcodesIO\API\Contract\CollectionInterface;

abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @var array
     */
    protected $data = [];

    public function getData() : array
    {
        return $this->data;
    }

    public function getResult() : CollectionInterface
    {
        return $this->data['result'];
    }

    public function isSuccessful() : bool
    {
        $statusCodeType = (int) ($this->data['status'] / 100);

        if ($statusCodeType === 2) {
            return true;
        }

        return false;
    }

    public function hasErrors() : bool
    {
        return !$this->isSuccessful();
    }

    public function hasValidResult() : bool
    {
        $result = $this->getResult()->getFirst();

        if (is_bool($result)) {
            return true === $result;
        }

        if ($result instanceof CollectionInterface) {
            return true;
        }

        if ($result instanceof Data) {
            return true;
        }

        return false;
    }
}
