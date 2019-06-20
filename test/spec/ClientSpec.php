<?php

namespace spec\PostcodesIO\API;

use Nyholm\Psr7\Uri;
use Prophecy\Argument;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\UriInterface;
use Psr\Http\Client\ClientInterface;
use PostcodesIO\API\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use spec\PostcodesIO\API\HttpMockClient;
use PostcodesIO\API\Client\ResponseCheck;
use PostcodesIO\API\Place\Client as PlaceClient;
use PostcodesIO\API\Contract\Psr17FactoryInterface;
use PostcodesIO\API\Outcode\Client as OutcodeClient;
use PostcodesIO\API\Postcode\Client as PostcodeClient;

class ClientSpec extends ClientObjectBehavior
{
    public function let (
        ClientInterface $client,
        Psr17FactoryInterface $psr17Factory
    ) {
        $this->beConstructedWith(
            $client,
            $psr17Factory,
            []
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function it_can_send_a_request(
        ClientInterface $client,
        Psr17FactoryInterface $psr17Factory
    ) {
        $response = $this->getResponse('empty');
        $request = $this->makeRequest('GET', 'https://test.local');

        $client->sendRequest($request)->willReturn($response);
        $this->send($request);

        $client->sendRequest($request)->shouldHaveBeenCalledOnce();
    }

    public function it_can_send_a_get_request(
        ClientInterface $client,
        Psr17FactoryInterface $psr17Factory
    ) {
        $uri = $this->makeUri('places');
        $query = ['q' => 'harlesden'];
        $response = $this->getResponse('places-osgb4000000074302858');
        $psr17Factory->createUri((string) $uri)->willReturn($uri);
        $uri = (string) $uri->withQuery(http_build_query($query));
        $request = $this->makeRequest('GET', $uri);
        $psr17Factory->createRequest('GET', $uri)->willReturn($request);
        $client->sendRequest($request)->willReturn($response);

        $this->get('places', $query)
            ->getBody()
            ->getContents()
            ->shouldBeLike((string) $response->getBody());

        $client->sendRequest($request)->shouldHaveBeenCalledOnce();
    }

    public function it_can_send_a_post_request(
        ClientInterface $client,
        Psr17FactoryInterface $psr17Factory
    ) {
        $endpoint = $this->baseUri;
        $url = 'postcodes';
        $data = [
            'postcodes' => ['PR3 0SG', 'M45 6GN', 'EX165BL']
        ];
        $stream = Stream::create(json_encode($data));
        $uri = new Uri($endpoint . $url);
        $request = $this->makeRequest('POST', $endpoint . $url);
        $response = $this->getResponse('postcodes-bulk-lookup');
        $psr17Factory->createStream(json_encode($data))->willReturn($stream);
        $psr17Factory->createUri($endpoint . $url)->willReturn($uri);
        $psr17Factory->createRequest('POST', $endpoint . $url)->willReturn($request);
        $request = $request->withBody($stream);
        $client->sendRequest($request)->willReturn($response);

        $this->post('postcodes', $data)
            ->getBody()
            ->getContents()
            ->shouldBeLike((string) $response->getBody());
    }

    public function it_can_get_the_postcode_client() {
        $this->getPostcodeClient()
            ->shouldBeAnInstanceOf(PostcodeClient::class);
    }

    public function it_can_get_the_place_client() {
        $this->getPlaceClient()->shouldBeAnInstanceOf(PlaceClient::class);
    }

    public function it_can_get_the_outcode_client() {
        $this->getOutcodeClient()->shouldBeAnInstanceOf(OutcodeClient::class);
    }
}
