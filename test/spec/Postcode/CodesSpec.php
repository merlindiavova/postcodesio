<?php

namespace spec\PostcodesIO\API\Postcode;

use PostcodesIO\API\Postcode\Codes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CodesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith('','','','','','','','');
        $this->shouldHaveType(Codes::class);
    }

    public function it_returns_given_data()
    {
        $codes = $this->getData()['result']['codes'];

        $this->beConstructedWith(
            $codes['admin_district'] ?? '',
            $codes['admin_county'] ?? '',
            $codes['admin_ward'] ?? '',
            $codes['parish'] ?? '',
            $codes['parliamentary_constituency'] ?? '',
            $codes['ccg'] ?? '',
            $codes['ced'] ?? '',
            $codes['nuts'] ?? ''
        );

        $this->getAdminDistrict()->shouldBeEqualTo($codes['admin_district']);
        $this->getAdminCounty()->shouldBeEqualTo($codes['admin_county']);
        $this->getAdminWard()->shouldBeEqualTo($codes['admin_ward']);
        $this->getParish()->shouldBeEqualTo($codes['parish']);
        $this->getParliamentaryConstituency()
            ->shouldBeEqualTo($codes['parliamentary_constituency']);
        $this->getCcg()->shouldBeEqualTo($codes['ccg']);
        $this->getCed()->shouldBeEqualTo($codes['ced']);
        $this->getNuts()->shouldBeEqualTo($codes['nuts']);
    }

    public function it_can_construct_from_an_array()
    {
        $codes = $this->getData()['result']['codes'];

        $this->beConstructedThrough('makeFromArray', [$codes]);

        $this->getAdminDistrict()->shouldBeEqualTo($codes['admin_district']);
        $this->getAdminCounty()->shouldBeEqualTo($codes['admin_county']);
        $this->getAdminWard()->shouldBeEqualTo($codes['admin_ward']);
        $this->getParish()->shouldBeEqualTo($codes['parish']);
        $this->getParliamentaryConstituency()
            ->shouldBeEqualTo($codes['parliamentary_constituency']);
        $this->getCcg()->shouldBeEqualTo($codes['ccg']);
        $this->getCed()->shouldBeEqualTo($codes['ced']);
        $this->getNuts()->shouldBeEqualTo($codes['nuts']);
    }

    public function getData(): array
    {
        $filename = __DIR__ . '/../../responses/postcode-nw10-4dg.json';
        return json_decode(file_get_contents($filename), true);
    }
}
