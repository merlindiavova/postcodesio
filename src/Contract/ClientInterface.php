<?php

declare(strict_types=1);

namespace PostcodesIO\API\Contract;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use PostcodesIO\API\Place\Client as PlaceClient;
use PostcodesIO\API\Postcode\Client as PostcodeClient;
use PostcodesIO\API\Outcode\Client as OutcodeClient;

interface ClientInterface
{
    public function get(string $uri, array $params = []): ResponseInterface;

    public function post(
        string $uri,
        array $params = []
    ): ResponseInterface;

    public function send(RequestInterface $request): ResponseInterface;

    public function getPlaceClient() : PlaceClient;

    public function getPostcodeClient() : PostcodeClient;

    public function getOutcodeClient() : OutcodeClient;
}
