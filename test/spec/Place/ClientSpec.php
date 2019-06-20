<?php

namespace spec\PostcodesIO\API\Place;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Place\Data;
use PostcodesIO\API\Place\Client;
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
        $responseStub = $this->getResponse('places-osgb4000000074302858');
        $client->get('places/osgb4000000074302858')->willReturn($responseStub);

        $response = $this->fetch('osgb4000000074302858');
        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);

        $firstResult = $result->getFirst();
        $firstResult->shouldBeAnInstanceOf(Data::class);

        $firstResult->getName1()->shouldBeEqualTo('Harlesden');
    }

    public function it_can_perform_a_search(ClientInterface $client)
    {
        $responseStub = $this->getResponse('places-query-fleet');
        $client->get('places?q=fleet')->willReturn($responseStub);

        $response = $this->fetchByQuery('fleet')
            ->getResult()
            ->getFirst()
            ->getName1()
            ->shouldBeEqualTo('Fleet');
    }

    public function it_can_perform_a_random_lookup(ClientInterface $client)
    {
        $codes = [
            1 => 'osgb4000000074578804',
            2 => 'osgb4000000074579735',
            3 => 'osgb4000000074302858',
        ];

        $code = $codes[mt_rand(1, 3)];

        $responseStub = $this->getResponse('places-' . $code);
        $client->get('random/places')->willReturn($responseStub);

        $response = $this->fetchRandom()
            ->getResult()
            ->getFirst()
            ->getCode()
            ->shouldBeEqualTo($code);
    }
}
