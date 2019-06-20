<?php

declare(strict_types=1);

namespace PostcodesIO\API\Client;

class Response extends AbstractResponse
{
    /**
     * @var array
     */
    protected $expectedParams = [];

    public function __construct(array $data)
    {
        $keys = array_keys($data);
        $missing = array_diff($this->expectedParams, $keys);

        if ($missing) {
            throw new \RuntimeException(
                'Missing expected response keys: ' . implode(', ', $missing)
            );
        }

        $this->data = $data;
    }
}
