<?php

declare(strict_types=1);

namespace PostcodesIO\API\Outcode;

use Psr\Http\Message\ResponseInterface;
use PostcodesIO\API\Client\Error;
use PostcodesIO\API\Client\Collection;
use PostcodesIO\API\Client\ResponseCheck;
use PostcodesIO\API\Contract\ClientInterface;
use PostcodesIO\API\Outcode\Response as OutcodeResponse;
use PostcodesIO\API\Contract\ResponseInterface as ClientResponseInterface;

final class Client
{
    /**
     * @var string
     */
    private $baseUri = 'outcodes';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ResponseCheck
     */
    private $responseCheck;

    public function __construct(
        ClientInterface $client,
        ResponseCheck $responseCheck
    ) {
        $this->client = $client;
        $this->responseCheck = $responseCheck;
    }

    public function fetch(
        string $outcode,
        array $params = []
    ): ClientResponseInterface {
        $uri = $this->makeUri($params, $outcode);
        return $this->processResponse($this->client->get($uri));
    }

    public function fetchNearestTo(
        string $outcode,
        array $params = []
    ) : ClientResponseInterface {
        $uri = $this->makeUri($params, $outcode . '/nearest');

        return $this->processResponse($this->client->get($uri));
    }

    public function fetchByReverseGeocode(
        float $longitude,
        float $latitude,
        array $params = []
    ): ClientResponseInterface {
        $uri = $this->makeUri(array_merge([
            'lon' => $longitude,
            'lat' => $latitude
        ], $params));

        return $this->processResponse($this->client->get($uri));
    }

    private function processResponse(
        ResponseInterface $response
    ) : ClientResponseInterface {
        $responseData = $this->processBody($response);

        if (isset($responseData['error'])) {
            return new Error($responseData);
        }

        $preparedResponseData = $this->prepareData($responseData);

        return new OutcodeResponse($preparedResponseData);
    }

    private function prepareData(array $data) : array
    {
        if (isset($data['result']) && is_bool($data['result'])) {
            $data['result'] = new Collection([$data['result']]);
        } elseif (empty($data['result'])) {
            $data['status'] = 404;
            $data['error'] = 'Outcode not found';
        } elseif (!empty($data['result'][0])) {
            $data['result'] = new Collection(
                $this->mapResults($data['result'])
            );
        } elseif (isset($data['result']['outcode'])) {
            $data['result'] = new Collection(
                $this->mapResults([$data['result']])
            );
        }

        return $data;
    }

    private function processBody(ResponseInterface $response) : array
    {
        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }

        $rawBody = $response->getBody()->getContents();
        $responseBody = json_decode($rawBody, true);

        $responseCheck = $this->responseCheck;
        $responseCheck($responseBody, $response->getStatusCode());

        return $responseBody;
    }

    private function mapResults(array $results): array
    {
        return array_map(function (array $result) {
            return Data::makeFromArray($result);
        }, $results);
    }

    private function makeUri(array $params = [], string $uri = ''): string
    {
        $uri = ($uri !== '') ? $this->baseUri . '/' . $uri : $this->baseUri;

        if (!empty($params)) {
            $uri = $uri . '?' . http_build_query($params);
        }

        return $uri;
    }
}
