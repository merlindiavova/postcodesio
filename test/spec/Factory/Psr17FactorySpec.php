<?php

namespace spec\PostcodesIO\API\Factory;

use Prophecy\Argument;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use PostcodesIO\API\Factory\Psr17Factory;

class Psr17FactorySpec extends ObjectBehavior
{
    public function let(
        RequestFactoryInterface $requestFactory,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory
    ) {
        $this->beConstructedWith(
            $requestFactory,
            $responseFactory,
            $streamFactory,
            $uriFactory
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Psr17Factory::class);
    }

    public function it_can_create_a_request(
        RequestFactoryInterface $requestFactory
    ) {
        $uri = 'https://test.local';
        $request = new Request('GET', $uri);
        $requestFactory->createRequest('GET', $uri)->willReturn($request);

        $this->createRequest('GET', $uri)
            ->getUri()
            ->__toString()
            ->shouldBeLike($uri);
    }

    public function it_can_create_a_response(
        ResponseFactoryInterface $responseFactory
    ) {
        $statusCode = 404;
        $reasonPhrase = 'Not found';
        $response = new Response($statusCode, [], null, '1.1', $reasonPhrase);
        $responseFactory->createResponse(404, 'Not found')
            ->willReturn($response);

        $createdResponse = $this->createResponse(404, 'Not found');
        $createdResponse->getStatusCode()->shouldBe($statusCode);
        $createdResponse->getReasonPhrase()->shouldBe($reasonPhrase);
    }

    public function it_can_create_a_stream_from_string(
        StreamFactoryInterface $streamFactory
    ) {
        $data = json_encode(['postcodes' => ['nw10 4dg']]);
        $stream = Stream::create($data);
        $streamFactory->createStream($data)->willReturn($stream);

        $createdStreams = $this->createStream($data);
        $createdStreams->__toString()->shouldBe($data);
    }

    public function it_can_create_a_stream_from_file(
        StreamFactoryInterface $streamFactory
    ) {
        $filename = __DIR__ . '/../../responses/postcode-nw10-4dg.json';
        $data = file_get_contents($filename);
        $stream = Stream::create($data);
        $streamFactory->createStreamFromFile($filename, 'r')
            ->willReturn($stream);

        $createdStream = $this->createStreamFromFile($filename);
        $createdStream->__toString()->shouldBe($data);
    }

    public function it_can_create_a_stream_from_resource(
        StreamFactoryInterface $streamFactory
    ) {
        $filename = __DIR__ . '/../../responses/postcode-nw10-4dg.json';
        $data = file_get_contents($filename);
        $resource = fopen($filename, 'r');
        $stream = Stream::create($resource);
        $streamFactory->createStreamFromResource($resource)
            ->willReturn($stream);

        $createdStream = $this->createStreamFromResource($resource);
        $createdStream->__toString()->shouldBe($data);
    }
}
