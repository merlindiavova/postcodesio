<?php

namespace spec\PostcodesIO\API\Outcode;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Outcode\Data;
use PostcodesIO\API\Outcode\Client;
use PostcodesIO\API\Client\ResponseCheck;
use PostcodesIO\API\Contract\ClientInterface;
use spec\PostcodesIO\API\ClientObjectBehavior;
use PostcodesIO\API\Contract\ResponseInterface;
use PostcodesIO\API\Contract\CollectionInterface;

class ClientSpec extends ClientObjectBehavior
{
    public function let(
        ClientInterface $client,
        ResponseCheck $responseCheck
    ) {
        $this->beConstructedWith($client, $responseCheck);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function it_can_perform_a_lookup(ClientInterface $client)
    {
        $responseStub = $this->getResponse('outcode-nw10');
        $client->get('outcodes/nw10')->willReturn($responseStub);

        $response = $this->fetch('nw10');
        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);

        $firstResult = $result->getFirst();
        $firstResult->shouldBeAnInstanceOf(Data::class);

        $firstResult->getAdminDistrict()->getLast()
            ->shouldBeEqualTo('Brent');
    }

    public function it_can_perform_a_nearest_lookup(ClientInterface $client)
    {
        $query = ['limit' => 5, 'radius' => 20000];
        $uri = 'outcodes/fy7/nearest?limit=5&radius=20000';

        $responseStub = $this->getResponse('outcode-nearest-fy7');
        $client->get($uri)->willReturn($responseStub);

        $response = $this->fetchNearestTo('fy7', $query);
        $response->getResult()->count()->shouldBe(5);

        $response->getFirstResult()
            ->getParish()
            ->getFirst()
            ->shouldBeEqualTo('Fleetwood');

        $response->getResult()
            ->getLast()
            ->getParish()
            ->getFirst()
            ->shouldBeEqualTo('Staining');
    }

    public function it_can_fetch_by_reverse_geocode(
        ClientInterface $client
    ) {
        $uri = 'outcodes?lon=-0.24805325540166&lat=51.541281425484&radius=2000&limit=100';
        $responseStub = $this->getResponse('outcode-long-lat-nw10');
        $client->get($uri)->willReturn($responseStub);

        $longitude = -0.24805325540166;
        $latitude = 51.541281425484;
        $params = ['radius' => 2000, 'limit' => 100];
        $response = $this->fetchByReverseGeocode($longitude, $latitude, $params);

        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);
        $result->count()->shouldEqual(2);

        $firstResult = $result->getFirst();
        $firstResult->shouldBeAnInstanceOf(Data::class);

        $firstResult->getAdminWard()->getFirst()
            ->shouldBeEqualTo('Welsh Harp');
    }
}
