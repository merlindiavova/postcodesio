<?php

namespace spec\PostcodesIO\API\Place;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Immutable\ValueObject\ValueObject;
use PostcodesIO\API\Place\Data;

class DataSpec extends ObjectBehavior
{
    public function let()
    {
        $data = $this->getData()['result'];

        $this->beConstructedWith(
            $data['code'] ?? '',
            $data['name_1'] ?? '',
            $data['name_1_lang'] ?? '',
            $data['name_2'] ?? '',
            $data['name_2_lang'] ?? '',
            $data['local_type'] ?? '',
            $data['outcode'] ?? '',
            $data['county_unitary'] ?? '',
            $data['county_unitary_type'] ?? '',
            $data['district_borough'] ?? '',
            $data['district_borough_type'] ?? '',
            $data['region'] ?? '',
            $data['country'] ?? '',
            $data['longitude'] ?? '',
            $data['latitude'] ?? '',
            $data['eastings'] ?? '',
            $data['northings'] ?? '',
            $data['min_eastings'] ?? '',
            $data['min_northings'] ?? '',
            $data['max_eastings'] ?? '',
            $data['max_northings'] ?? ''
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Data::class);
        $this->shouldHaveType(ValueObject::class);
    }

    public function it_returns_given_attributes()
    {
        $data = $this->getData()['result'];

        $this->getCode()->shouldEqual($data['code']);
        $this->getName1()->shouldEqual($data['name_1']);
        $this->getName1Lang()->shouldEqual($data['name_1_lang'] ?? '');
        $this->getName2()->shouldEqual($data['name_2'] ?? '');
        $this->getName2Lang()->shouldEqual($data['name_2_lang'] ?? '');
        $this->getLocalType()->shouldEqual($data['local_type']);
        $this->getOutcode()->shouldEqual($data['outcode']);
        $this->getCountyUnitary()->shouldEqual($data['county_unitary'] ?? '');
        $this->getCountyUnitaryType()->shouldEqual($data['county_unitary_type'] ?? '');
        $this->getDistrictBorough()->shouldEqual($data['district_borough'] ?? '');
        $this->getDistrictBoroughType()->shouldEqual($data['district_borough_type'] ?? '');
        $this->getRegion()->shouldEqual($data['region']);
        $this->getCountry()->shouldEqual($data['country']);
        $this->getLongitude()->shouldEqual($data['longitude']);
        $this->getLatitude()->shouldEqual($data['latitude']);
        $this->getEastings()->shouldEqual($data['eastings']);
        $this->getNorthings()->shouldEqual($data['northings']);
        $this->getMinEastings()->shouldEqual($data['min_eastings']);
        $this->getMinNorthings()->shouldEqual($data['min_northings']);
        $this->getMaxEastings()->shouldEqual($data['max_eastings']);
        $this->getMaxNorthings()->shouldEqual($data['max_northings']);
    }

    public function it_can_construct_from_given_array()
    {
        $data = $this->getData()['result'];

        $this->beConstructedThrough('makeFromArray', [$data]);

        $this->getCode()->shouldEqual($data['code']);
        $this->getName1()->shouldEqual($data['name_1']);
        $this->getName1Lang()->shouldEqual($data['name_1_lang'] ?? '');
        $this->getName2()->shouldEqual($data['name_2'] ?? '');
        $this->getName2Lang()->shouldEqual($data['name_2_lang'] ?? '');
        $this->getLocalType()->shouldEqual($data['local_type']);
        $this->getOutcode()->shouldEqual($data['outcode']);
        $this->getCountyUnitary()->shouldEqual($data['county_unitary'] ?? '');
        $this->getCountyUnitaryType()->shouldEqual($data['county_unitary_type'] ?? '');
        $this->getDistrictBorough()->shouldEqual($data['district_borough'] ?? '');
        $this->getDistrictBoroughType()->shouldEqual($data['district_borough_type'] ?? '');
        $this->getRegion()->shouldEqual($data['region']);
        $this->getCountry()->shouldEqual($data['country']);
        $this->getLongitude()->shouldEqual($data['longitude']);
        $this->getLatitude()->shouldEqual($data['latitude']);
        $this->getEastings()->shouldEqual($data['eastings']);
        $this->getNorthings()->shouldEqual($data['northings']);
        $this->getMinEastings()->shouldEqual($data['min_eastings']);
        $this->getMinNorthings()->shouldEqual($data['min_northings']);
        $this->getMaxEastings()->shouldEqual($data['max_eastings']);
        $this->getMaxNorthings()->shouldEqual($data['max_northings']);
    }

    public function getData(): array
    {
        $filename = __DIR__ . '/../../responses/places-osgb4000000074302858.json';
        return json_decode(file_get_contents($filename), true);
    }
}
