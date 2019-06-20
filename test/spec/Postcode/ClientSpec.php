<?php

namespace spec\PostcodesIO\API\Postcode;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Contract;
use PostcodesIO\API\Exception;
use PostcodesIO\API\Postcode\Data;
use PostcodesIO\API\Postcode\Client;
use PostcodesIO\API\Client\Collection;
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
        $responseStub = $this->getResponse('postcode-nw10-4dg');
        $client->get('postcodes/NW104DG')->willReturn($responseStub);

        $response = $this->fetch('NW104DG');
        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);

        $firstResult = $result->getFirst();
        $firstResult->shouldBeAnInstanceOf(Data::class);

        $firstResult->getPostcode()->shouldBeEqualTo('NW10 4DG');
    }

    public function it_can_perform_a_bulk_lookup(ClientInterface $client)
    {
        $postCodes = ['PR3 0SG', 'M45 6GN', 'EX165BL'];
        $params = ['postcodes' => $postCodes];
        $responseStub = $this->getResponse('postcodes-bulk-lookup');
        $client->post('postcodes', $params)->willReturn($responseStub);

        $response = $this->fetchByBulk(['PR3 0SG', 'M45 6GN', 'EX165BL']);
        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);

        $result->getFirst()
            ->getPostcode()
            ->shouldBeEqualTo('PR3 0SG');

        $result->getLast()
            ->getPostcode()
            ->shouldBeEqualTo('EX16 5BL');

        $result->getLast()
            ->getQuery()
            ->getFirst()
            ->shouldBe('EX165BL');
    }

    public function it_can_perform_a_bulk_lookup_with_filters(ClientInterface $client)
    {
        $postCodes = ['PR3 0SG', 'M45 6GN', 'EX165BL'];
        $requestParams = ['postcodes' => $postCodes];
        $uriParams = ['filter' => 'postcode,longitude,latitude'];
        $uri = 'postcodes?' . http_build_query($uriParams);
        $responseStub = $this->getResponse('postcodes-bulk-lookup');
        $client->post($uri , $requestParams)->willReturn($responseStub);

        $response = $this->fetchByBulk($postCodes, $uriParams);

        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);

        $firstResult = $result->getFirst();
        $firstResult->shouldBeAnInstanceOf(Data::class);

        $firstResult->getPostcode()->shouldBeEqualTo('PR3 0SG');
    }

    public function it_can_fetch_by_reverse_geocode(
        ClientInterface $client
    ) {
        $uri = 'postcodes?lon=-0.28339894794254&lat=51.566737459557&radius=2000&limit=100';
        $responseStub = $this->getResponse('postcode-long-lat-wembley-park');
        $client->get($uri)->willReturn($responseStub);

        $longitude = -0.283398947942544;
        $latitude = 51.5667374595571;
        $params = ['radius' => 2000, 'limit' => 100];
        $response = $this->fetchByReverseGeocode($longitude, $latitude, $params);

        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);
        $result->count()->shouldEqual(10);

        $firstResult = $result->getFirst();
        $firstResult->shouldBeAnInstanceOf(Data::class);

        $firstResult->getPostcode()->shouldBeEqualTo('HA9 9PD');
    }

    public function it_can_fetch_by_bulk_reverse_geocode(
        ClientInterface $client
    ) {
        $geoQuery = [
            [
                'longitude' => 0.629834723775309,
                'latitude' => 51.7923246977375
            ],
            [
                'longitude' =>  -3.03441830940723,
                'latitude' => 53.9137367325845,
                'radius' => 1000,
                'limit' => 5
            ],
        ];

        $responseStub = $this->getResponse('bulk-reverse-geocoding');
        $client->post(
            'postcodes',
            ['geolocations' => $geoQuery]
        )->willReturn($responseStub);

        $response = $this->fetchByBulkReverseGeocode($geoQuery);

        $response->shouldHaveType(ResponseInterface::class);
        $response->hasErrors()->shouldBe(false);

        $result = $response->getResult();
        $result->shouldBeAnInstanceOf(CollectionInterface::class);
        $result->count()->shouldEqual(9);

        $firstResult = $result->getFirst();
        $firstResult->shouldBeAnInstanceOf(Data::class);
        $firstResult->getPostcode()->shouldBeEqualTo('CM8 1EF');

        $lastResult = $result->getLast();
        $lastResult->getPostcode()->shouldBeEqualTo('FY7 8HP');
    }

    public function it_can_perform_a_postcode_search(ClientInterface $client)
    {
        $responseStub = $this->getResponse('postcode-nw10-4dg');
        $client->get('postcodes?q=nw104dg')->willReturn($responseStub);

        $response = $this->fetchByQuery('nw104dg')
            ->getResult()
            ->getFirst()
            ->getPostcode()
            ->shouldBeEqualTo('NW10 4DG');
    }

    public function it_can_perform_a_random_lookup(ClientInterface $client)
    {
        $postcodes = [
            1 => 'nw10-4dg',
            2 => 'fy7-6jz',
            3 => 'ha0-2tf',
        ];

        $postcodeSlug = $postcodes[mt_rand(1, 3)];
        $postcode = strtoupper(str_replace('-', ' ', $postcodeSlug));

        $responseStub = $this->getResponse('postcode-' . $postcodeSlug);
        $client->get('random/postcodes')->willReturn($responseStub);

        $response = $this->fetchRandom()
            ->getResult()
            ->getFirst()
            ->getPostcode()
            ->shouldBeEqualTo($postcode);
    }

    public function it_request_postcode_validation_and_return_success_result(
        ClientInterface $client
    ) {
        $responseStub = $this->getResponse('postcode-valid-validation');

        $client->get("postcodes/ha02tf/validate")->willReturn($responseStub);

        $this->validate('ha02tf')->hasValidResult()->shouldBe(true);
    }

    public function it_request_postcode_validation_and_return_failed_result(
        ClientInterface $client
    ) {
        $responseStub = $this->getResponse('postcode-failed-validation');

        $client->get("postcodes/2tf/validate")->willReturn($responseStub);

        $this->validate('2tf')->getFirstResult()->shouldBe(false);
    }

    public function it_can_perform_a_nearest_lookup(ClientInterface $client)
    {
        $query = ['limit' => 6, 'radius' => 1000];
        $uri = 'postcodes/fy76jz/nearest?limit=6&radius=1000';

        $responseStub = $this->getResponse('postcode-nearest-fy7-6jz');
        $client->get($uri)->willReturn($responseStub);

        $response = $this->fetchNearestTo('fy76jz', $query);
        $response->getResult()->count()->shouldBe(6);

        $response->getFirstResult()
            ->getPostcode()
            ->shouldBeEqualTo('FY7 6JZ');

        $response->getResult()
            ->getLast()
            ->getPostcode()
            ->shouldBeEqualTo('FY7 6DS');
    }

    public function it_can_perform_a_autocomplete_lookup(
        ClientInterface $client
    )  {
        $query = ['limit' => 3];
        $uri = 'postcodes/nw3/autocomplete?limit=3';

        $responseStub = $this->getResponse('postcode-autocomplete-nw3');
        $client->get($uri)->willReturn($responseStub);

        $response = $this->fetchAutoComplete('nw3', $query);
        $response->getResult()->count()->shouldBe(3);
    }

    public function it_fetch_terminated_postcodes(
        ClientInterface $client
    )  {
        $uri = 'terminated_postcodes/E1W 1UU';

        $responseStub = $this->getResponse('postcode-terminated');
        $client->get($uri)->willReturn($responseStub);

        $response = $this->fetchTerminated('E1W 1UU');
        $result = $response->getFirstResult();

        $result->getYearTerminated()->shouldEqual(2015);
        $result->getMonthTerminated()->shouldEqual(2);
        $result->getPostcode()->shouldBeEqualTo('E1W 1UU');
    }

    public function it_throws_an_exception_for_validation_errors(
        ClientInterface $client,
        ResponseCheck $responseCheck
    ) {
        $response = $this->getResponse('invalid-post-code');
        $data = json_decode($response->getBody()->getContents(), true);

        $client->get('postcodes/nw114D.')->willReturn($response);

        $responseCheck->__invoke($data, 404)
            ->willThrow(new Exception\Validation('Invalid postcode', 400));
    }
}
