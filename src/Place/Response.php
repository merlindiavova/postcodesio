<?php

declare(strict_types=1);

namespace PostcodesIO\API\Place;

use Psr\Http\Message\ResponseInterface;
use PostcodesIO\API\Contract\ClientInterface;
use PostcodesIO\API\Client\Response as ClientResponse;

final class Response extends ClientResponse
{
    /**
     * @var array
     */
    protected $expected = [
        'result'
    ];

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * @return mixed
     */
    public function getFirstResult()
    {
        return $this->getResult()->getFirst();
    }
}
