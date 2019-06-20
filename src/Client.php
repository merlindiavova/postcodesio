<?php

declare(strict_types=1);

namespace PostcodesIO\API;

use Immutable\Immutable;
use Psr\Http\Message\UriInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use PostcodesIO\API\Client\ResponseCheck;
use PostcodesIO\API\Factory\Psr17Factory;
use PostcodesIO\API\Place\Client as PlaceClient;
use PostcodesIO\API\Contract\Psr17FactoryInterface;
use PostcodesIO\API\Outcode\Client as OutcodeClient;
use PostcodesIO\API\Postcode\Client as PostcodeClient;
use PostcodesIO\API\Contract\ClientInterface as AmeClientInterface;

final class Client extends Immutable implements AmeClientInterface
{
    /**
     * @var string
     */
    const VERSION = '0.1.0';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Psr17FactoryInterface
     */
    private $psr17Factory;

    /**
     * @var ResponseCheck
     */
    private $responseCheck;

    /**
     * @var array
     */
    private $apiClientMap = [
        PlaceClient::class,
        PostcodeClient::class,
        OutcodeClient::class
    ];

    /**
     * @var array
     */
    private $cachedApiClients = [];

    /**
     * @var string
     */
    private $baseUri = 'https://api.postcodes.io/';

    /**
     * Create new instance
     *
     * @param  ClientInterface $client
     * @param  Psr17FactoryInterface $psr17Factory
     * @param  array $additionalApiClients
     */
    public function __construct(
        ClientInterface $client,
        Psr17FactoryInterface $psr17Factory,
        array $additionalApiClients = []
    ) {
        $this->client = $client;
        $this->psr17Factory = $psr17Factory;
        $this->responseCheck = new ResponseCheck();
        $this->apiClientMap = $this->apiClientMap + $additionalApiClients;

        parent::__construct();
    }

    public function getPlaceClient() : PlaceClient
    {
        return $this->getApiClient(PlaceClient::class);
    }

    public function getPostcodeClient() : PostcodeClient
    {
        return $this->getApiClient(PostcodeClient::class);
    }

    public function getOutcodeClient() : OutcodeClient
    {
        return $this->getApiClient(OutcodeClient::class);
    }

    public function get(string $uri, array $params = []): ResponseInterface
    {
        $preparedUrl = $this->makeUri($uri, $params);
        $request = $this->psr17Factory->createRequest('GET', $preparedUrl);

        return $this->send($request);
    }

    public function post(
        string $uri,
        array $params = []
    ): ResponseInterface {
        $preparedUri = (string) $this->makeUri($uri);

        $request = $this->psr17Factory->createRequest('POST', $preparedUri);
        $request = $request->withHeader('Content-Type', 'application/json');

        if (!empty($params)) {
            $encodedData = $this->encodeData($params);
            $stream = $this->psr17Factory->createStream($encodedData);
            $request = $request->withBody($stream);
        }

        return $this->send($request);
    }

    /**
     * Wrap the HTTP Client, creates a new PSR-7 request.
     * Adds any missing required headers.
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        if (!$request->hasHeader('User-Agent')) {
            $userAgent = [
                'amesplash-postcodesio-api-php/' . self::VERSION,
                'php/' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION
            ];

            $request = $request->withHeader(
                'User-Agent',
                implode(" ", $userAgent)
            );
        }

        if (!$request->hasHeader('Content-Type')) {
            $request = $request->withHeader(
                'Content-Type',
                'application/json'
            );
        }

        return $this->client->sendRequest($request);
    }

    private function encodeData(array $data) : string
    {
        $encodedData = json_encode($data);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                'Error encoding data: ' . json_last_error_msg()
            );
        }

        /** @var string */
        return (string) $encodedData;
    }

    private function makeUri(string $uri, array $params = []): UriInterface
    {
        $preparedUri = $this->psr17Factory->createUri($this->baseUri . $uri);

        if (!empty($params)) {
            $preparedUri = $preparedUri->withQuery(http_build_query($params));
        }

        return $preparedUri;
    }

    private function hasApiClient(string $api): bool
    {
        return in_array($api, $this->apiClientMap);
    }

    /**
     * @psalm-param class-string $apiClient
     * @return mixed
     */
    private function getApiClient(string $apiClient)
    {
        if (isset($this->cachedApiClients[$apiClient])) {
            return $this->cachedApiClients[$apiClient];
        }

        if (!$this->hasApiClient($apiClient)) {
            throw new \RuntimeException(sprintf(
                'API [%s] not found',
                $apiClient
            ));
        }

        $instance = new $apiClient($this, $this->responseCheck);

        $this->cachedApiClients[$apiClient] = $instance;
        return $instance;
    }
}
