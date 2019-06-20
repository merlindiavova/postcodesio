<?php

declare(strict_types=1);

namespace PostcodesIO\API\Client;

use PostcodesIO\API\Exception;
use PostcodesIO\API\Contract\CollectionInterface;

final class Error extends Response
{
    /**
     * @var array
     */
    protected $expectedParams = ['status', 'error'];

    public function hasErrors() : bool
    {
        return true;
    }

    public function isSuccessful() : bool
    {
        return false;
    }

    public function getCode() : int
    {
        return $this->data['status'];
    }

    public function getMessage() : string
    {
        return $this->data['error'];
    }

    public function getResult() : CollectionInterface
    {
        throw new Exception\RuntimeException(
            'Error Response do not contain any results.'
        );
    }
}
