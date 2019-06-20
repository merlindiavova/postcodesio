<?php

declare(strict_types=1);

namespace PostcodesIO\API\Client;

use PostcodesIO\API\Exception;

class ResponseCheck
{
    public function __invoke(array $body, int $statusCode): void
    {
        $statusCodeType = (int) ($statusCode / 100);

        if ($statusCodeType === 2) {
            return;
        }

        // If it's a 5xx error, throw an exception
        if ($statusCodeType == 5) {
            throw new Exception\Server($body['error'], $statusCode);
        }

        // Otherwise it's a 4xx,
        if ($body['error'] === 'Resource not found') {
            throw new Exception\ResourceNotFound($body['error'], 404);
        }

        if (strpos(strtolower($body['error']), 'not found') !== false) {
            throw new Exception\NotFound($body['error'], 404);
        }

        throw new Exception\Validation($body['error'], 400);
    }
}
