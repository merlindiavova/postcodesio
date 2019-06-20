<?php

namespace spec\PostcodesIO\API;

use Nyholm\Psr7\Uri;
use Prophecy\Argument;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\UriInterface;
use PostcodesIO\API\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use spec\PostcodesIO\API\HttpMockClient;
use PostcodesIO\API\Factory\Psr17FactoryInterface;

abstract class ClientObjectBehavior extends ObjectBehavior
{
    /**
     * @var string
     */
    protected $baseUri = 'https://api.postcodes.io/';

    protected function makeRequest(
        string $method,
        string $uri,
        array $params = []
    ): RequestInterface {
        $request = new Request($method, $uri);

        $userAgent = [
            'amesplash-uka-api-php/' . Client::VERSION,
            'php/' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION
        ];

        $request = $request->withHeader(
            'User-Agent',
            implode(" ", $userAgent)
        );

        $request = $request->withHeader(
            'Content-Type', 'application/json'
        );

        if ($method === 'POST') {
            $request = $request->withBody(
                Stream::create(json_encode($params))
            );
        }

        return $request;
    }

    protected function makeUri(string $uri): UriInterface
    {
        return new Uri($this->baseUri . $uri);
    }

    /**
     * Get the API response we'd expect for a call to the API.
     */
    protected function getResponse(
        string $file,
        int $statusCode = 200
    ): ResponseInterface {
        return new Response(
            $statusCode,
            [],
            fopen(__DIR__ . '/../responses/' . $file . '.json', 'r')
        );
    }
}
