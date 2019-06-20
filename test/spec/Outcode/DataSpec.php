<?php

namespace spec\PostcodesIO\API\Outcode;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Outcode\Data;
use PostcodesIO\API\Client\Collection;

class DataSpec extends ObjectBehavior
{
    public function let()
    {
        $data = $this->getData()['result'];

        $this->beConstructedWith(
            $data['outcode'] ?? '',
            $data['longitude'] ?? '',
            $data['latitude'] ?? '',
            new Collection($data['admin_district'] ?? []),
            new Collection($data['parish'] ?? []),
            new Collection($data['admin_county'] ?? []),
            new Collection($data['admin_ward'] ?? []),
            new Collection($data['country'] ?? []),
            $data['northings'] ?? null,
            $data['eastings'] ?? null
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Data::class);
    }

    public function it_can_construct_with_given_data()
    {
        $data = $this->getData()['result'];

        $this->getOutcode()->shouldEqual($data['outcode']);
        $this->getLongitude()->shouldEqual($data['longitude']);
        $this->getLatitude()->shouldEqual($data['latitude']);
        $this->getNorthings()->shouldEqual($data['northings']);
        $this->getEastings()->shouldEqual($data['eastings']);

        $this->getAdminDistrict()
            ->getArrayCopy()->shouldEqual($data['admin_district']);
        $this->getParish()
            ->getArrayCopy()->shouldEqual($data['parish']);
        $this->getAdminCounty()
            ->getArrayCopy()->shouldEqual($data['admin_county']);
        $this->getAdminWard()
            ->getArrayCopy()->shouldEqual($data['admin_ward']);
        $this->getCountry()
            ->getArrayCopy()->shouldEqual($data['country']);
    }

    public function it_can_construct_from_given_array()
    {
        $data = $this->getData()['result'];
        $this->beConstructedThrough('makeFromArray', [$data]);

        $this->getOutcode()->shouldEqual($data['outcode']);
        $this->getLongitude()->shouldEqual($data['longitude']);
        $this->getLatitude()->shouldEqual($data['latitude']);
        $this->getNorthings()->shouldEqual($data['northings']);
        $this->getEastings()->shouldEqual($data['eastings']);

        $this->getAdminDistrict()
            ->getArrayCopy()->shouldEqual($data['admin_district']);
        $this->getParish()
            ->getArrayCopy()->shouldEqual($data['parish']);
        $this->getAdminCounty()
            ->getArrayCopy()->shouldEqual($data['admin_county']);
        $this->getAdminWard()
            ->getArrayCopy()->shouldEqual($data['admin_ward']);
        $this->getCountry()
            ->getArrayCopy()->shouldEqual($data['country']);
    }

    public function getData(): array
    {
        $filename = __DIR__ . '/../../responses/outcode-nw10.json';
        return json_decode(file_get_contents($filename), true);
    }
}
