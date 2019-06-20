<?php

declare(strict_types=1);

namespace PostcodesIO\API\Postcode;

use Psr\Http\Message\ResponseInterface;
use PostcodesIO\API\Client\Error;
use PostcodesIO\API\Client\Collection;
use PostcodesIO\API\Client\ResponseCheck;
use PostcodesIO\API\Contract\ClientInterface;
use PostcodesIO\API\Postcode\Response as PostcodeResponse;
use PostcodesIO\API\Contract\ResponseInterface as ClientResponseInterface;

final class Client
{
    /**
     * @var string
     */
    private $baseUri = 'postcodes';

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
        string $postcode,
        array $params = []
    ): ClientResponseInterface {
        $uri = $this->makeUri($params, $postcode);

        return $this->processResponse($this->client->get($uri));
    }

    public function fetchByBulk(
        array $postcodes,
        array $params = []
    ): ClientResponseInterface {
        $uri = $this->makeUri($params);

        return $this->processResponse($this->client->post($uri, [
            'postcodes' => $postcodes
        ]));
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

    public function fetchByBulkReverseGeocode(
        array $geoQuery,
        array $params = []
    ): ClientResponseInterface {
        $uri = $this->makeUri($params);

        return $this->processResponse($this->client->post($uri, [
            'geolocations' => $geoQuery
        ]));
    }

    public function fetchByQuery(
        string $query,
        array $params = []
    ) : ClientResponseInterface {
        $uri = $this->makeUri(array_merge(['q' => $query], $params));
        return $this->processResponse($this->client->get($uri));
    }

    public function validate(
        string $subject,
        array $params = []
    ) : ClientResponseInterface {
        $uri = $this->makeUri($params, $subject . '/validate');

        return $this->processResponse($this->client->get($uri));
    }

    public function fetchRandom(
        array $params = []
    ) : ClientResponseInterface {
        $uri = 'random/' . $this->makeUri($params);
        return $this->processResponse($this->client->get($uri));
    }

    public function fetchNearestTo(
        string $postcode,
        array $params = []
    ) : ClientResponseInterface {
        $uri = $this->makeUri($params, $postcode . '/nearest');

        return $this->processResponse($this->client->get($uri));
    }

    public function fetchAutoComplete(
        string $subject,
        array $params = []
    ) : ClientResponseInterface {
        $uri = $this->makeUri($params, $subject . '/autocomplete');

        return $this->processResponse($this->client->get($uri));
    }

    public function fetchTerminated(
        string $postcode,
        array $params = []
    ) : ClientResponseInterface {
        $uri = 'terminated_' . $this->makeUri($params, $postcode);

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

        return new PostcodeResponse($preparedResponseData);
    }

    private function prepareData(array $data): array
    {
        if (isset($data['result']) && is_bool($data['result'])) {
            $data['result'] = new Collection([$data['result']]);
        } elseif (empty($data['result'])) {
            $data['status'] = 404;
            $data['error'] = 'Postcode not found';
        } elseif (!empty($data['result'][0]['query'])) {
            $data['result'] = new Collection(
                $this->mapQueryResults($data['result'])
            );
        } elseif (!empty($data['result'][0])) {
            $data['result'] = new Collection(
                $this->mapResults($data['result'])
            );
        } elseif (isset($data['result']['postcode'])) {
            $data['result'] = new Collection(
                $this->mapResults([$data['result']])
            );
        }

        return $data;
    }

    private function mapQueryResults(array $results): array
    {
        $resultsSet = array_map(function (array $queryResult) {
            $queryResult['query'] = (array) $queryResult['query'];

            if (!isset($queryResult['result'][0])) {
                $queryResult['result'] = [$queryResult['result']];
            }

            $queryResult['result'] = array_map(
                function (array $result) use ($queryResult) {
                    $result['query'] = new Collection($queryResult['query']);
                    return $result;
                },
                $queryResult['result']
            );

            $queryResult['result'] = $this->mapResults($queryResult['result']);

            return $queryResult['result'];
        }, $results);

        $mergedResultsSet = [];
        foreach ($resultsSet as $resultSet) {
            $mergedResultsSet = array_merge($mergedResultsSet, $resultSet);
        }

        return $mergedResultsSet;
    }

    private function mapResults(array $results): array
    {
        if (!isset($results[0]['postcode'])) {
            return $results;
        }

        return array_map(function (array $result) {
            if (isset($result['month_terminated'])) {
                return TerminatedData::makeFromArray($result);
            }

            if (isset($result['codes'])) {
                $result['codes'] = Codes::makeFromArray($result['codes']);
            }

            return Data::makeFromArray($result);
        }, $results);
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

    private function makeUri(array $params = [], string $uri = ''): string
    {
        $uri = ($uri !== '') ? $this->baseUri . '/' . $uri : $this->baseUri;

        if (!empty($params)) {
            $uri = $uri . '?' . http_build_query($params);
        }

        return $uri;
    }
}
