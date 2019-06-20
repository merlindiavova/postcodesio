<?php

declare(strict_types=1);

namespace PostcodesIO\API\Contract;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

interface Psr17FactoryInterface
{
    /**
     * Create a new request.
     *
     * @param string $method The HTTP method associated with the request.
     * @param UriInterface|string $uri The URI associated with the request.
     * @see https://www.php-fig.org/psr/psr-17/#21-requestfactoryinterface
     */
    public function createRequest(string $method, $uri): RequestInterface;

    /**
     * Create a new response.
     *
     * @param int $code The HTTP status code. Defaults to 200.
     * @param string $reasonPhrase The reason phrase to associate with the
     * status code in the generated response.
     * @see https://www.php-fig.org/psr/psr-17/#22-responsefactoryinterface
     */
    public function createResponse(
        int $code = 200,
        string $reasonPhrase = ''
    ): ResponseInterface;

    /**
     * Create a new stream from a string.
     *
     * The stream SHOULD be created with a temporary resource.
     *
     * @param string $content String content with which to populate the stream.
     * @see https://www.php-fig.org/psr/psr-17/#24-streamfactoryinterface
     */
    public function createStream(string $content = ''): StreamInterface;

    /**
     * Create a stream from an existing file.
     *
     * The file MUST be opened using the given mode, which may be any mode
     * supported by the `fopen` function.
     *
     * The `$filename` MAY be any string supported by `fopen()`.
     *
     * @param string $filename The filename or stream URI to use as basis of stream.
     * @param string $mode The mode with which to open the underlying filename/stream.
     *
     * @throws \RuntimeException If the file cannot be opened.
     * @throws \InvalidArgumentException If the mode is invalid.
     * @see https://www.php-fig.org/psr/psr-17/#24-streamfactoryinterface
     */
    public function createStreamFromFile(
        string $filename,
        string $mode = 'r'
    ): StreamInterface;

    /**
     * Create a new stream from an existing resource.
     *
     * The stream MUST be readable and may be writable.
     *
     * @param resource $resource The PHP resource to use as the basis for the stream.
     * @see https://www.php-fig.org/psr/psr-17/#24-streamfactoryinterface
     */
    public function createStreamFromResource($resource): StreamInterface;

    /**
     * Create a new URI.
     *
     * @param string $uri The URI to parse.
     *
     * @throws \InvalidArgumentException If the given URI cannot be parsed.
     * @see https://www.php-fig.org/psr/psr-17/#26-urifactoryinterface
     */
    public function createUri(string $uri = ''): UriInterface;
}
