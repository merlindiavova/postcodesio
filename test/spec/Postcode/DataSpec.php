<?php

namespace spec\PostcodesIO\API\Postcode;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Postcode\Data;
use PostcodesIO\API\Postcode\Codes;
use PostcodesIO\API\Client\Collection;

class DataSpec extends ObjectBehavior
{
    public function let()
    {
        $data = $this->getData()['result'];

        $this->beConstructedWith(
            $data['postcode'] ?? '',
            $data['quality'] ?? 0,
            $data['eastings'] ?? 0,
            $data['northings'] ?? 0,
            $data['country'] ?? '',
            $data['nhs_ha'] ?? '',
            $data['admin_county'] ?? '',
            $data['admin_district'] ?? '',
            $data['admin_ward'] ?? '',
            $data['longitude'] ?? '',
            $data['latitude'] ?? '',
            $data['parliamentary_constituency'] ?? '',
            $data['european_electoral_region'] ?? '',
            $data['primary_care_trust'] ?? '',
            $data['region'] ?? '',
            $data['parish'] ?? '',
            $data['lsoa'] ?? '',
            $data['msoa'] ?? '',
            $data['outcode'] ?? '',
            $data['ced'] ?? '',
            $data['ccg'] ?? '',
            $data['nuts'] ?? '',
            Codes::makeFromArray($data['codes']),
            $data['query'] ?? new Collection([])
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Data::class);
    }

    public function it_returns_given_attributes()
    {
        $data = $this->getData()['result'];

        $this->getPostcode()->shouldEqual($data['postcode']);
        $this->getQuality()->shouldReturn($data['quality']);
        $this->getEastings()->shouldReturn($data['eastings']);
        $this->getNorthings()->shouldReturn($data['northings']);
        $this->getCountry()->shouldEqual($data['country']);
        $this->getNhsHa()->shouldEqual($data['nhs_ha']);
        $this->getAdminCounty()->shouldEqual($data['admin_county'] ?? '');
        $this->getAdminDistrict()->shouldEqual($data['admin_district']);
        $this->getAdminWard()->shouldEqual($data['admin_ward']);
        $this->getLongitude()->shouldReturn($data['longitude']);
        $this->getLatitude()->shouldReturn($data['latitude']);
        $this->getParliamentaryConstituency()
            ->shouldEqual($data['parliamentary_constituency']);
        $this->getEuropeanElectoralRegion()->shouldEqual($data['european_electoral_region']);
        $this->getPrimaryCareTrust()->shouldEqual($data['primary_care_trust']);
        $this->getRegion()->shouldEqual($data['region']);
        $this->getParish()->shouldEqual($data['parish']);
        $this->getLsoa()->shouldEqual($data['lsoa']);
        $this->getMsoa()->shouldEqual($data['msoa']);
        $this->getCed()->shouldEqual($data['ced'] ?? '');
        $this->getCcg()->shouldEqual($data['ccg']);
        $this->getNuts()->shouldEqual($data['nuts']);
        $this->getCodes()->shouldReturnAnInstanceOf(Codes::class);
    }

    public function it_can_construct_from_given_array()
    {
        $data = $this->getData()['result'];

        $this->beConstructedThrough('makeFromArray', [$data]);

        $this->getPostcode()->shouldEqual($data['postcode']);
        $this->getQuality()->shouldReturn($data['quality']);
        $this->getEastings()->shouldReturn($data['eastings']);
        $this->getNorthings()->shouldReturn($data['northings']);
        $this->getCountry()->shouldEqual($data['country']);
        $this->getNhsHa()->shouldEqual($data['nhs_ha']);
        $this->getAdminCounty()->shouldEqual($data['admin_county'] ?? '');
        $this->getAdminDistrict()->shouldEqual($data['admin_district']);
        $this->getAdminWard()->shouldEqual($data['admin_ward']);
        $this->getLongitude()->shouldReturn($data['longitude']);
        $this->getLatitude()->shouldReturn($data['latitude']);
        $this->getParliamentaryConstituency()
            ->shouldEqual($data['parliamentary_constituency']);
        $this->getEuropeanElectoralRegion()->shouldEqual($data['european_electoral_region']);
        $this->getPrimaryCareTrust()->shouldEqual($data['primary_care_trust']);
        $this->getRegion()->shouldEqual($data['region']);
        $this->getParish()->shouldEqual($data['parish']);
        $this->getLsoa()->shouldEqual($data['lsoa']);
        $this->getMsoa()->shouldEqual($data['msoa']);
        $this->getCed()->shouldEqual($data['ced'] ?? '');
        $this->getCcg()->shouldEqual($data['ccg']);
        $this->getNuts()->shouldEqual($data['nuts']);
        $this->getCodes()->shouldReturnAnInstanceOf(Codes::class);
    }

    public function getData(): array
    {
        $filename = __DIR__ . '/../../responses/postcode-nw10-4dg.json';
        return json_decode(file_get_contents($filename), true);
    }
}
