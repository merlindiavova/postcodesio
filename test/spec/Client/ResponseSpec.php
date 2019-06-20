<?php

namespace spec\PostcodesIO\API\Client;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Client\Response;
use PostcodesIO\API\Contract\ResponseInterface;

class ResponseSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'status' => 200,
            'result' => [
                'postcode' => 'NW10 4DG'
            ]
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Response::class);
        $this->shouldHaveType(ResponseInterface::class);
    }

    public function it_should_return_correct_error_checks()
    {
        $this->hasErrors()->shouldBe(false);
        $this->isSuccessful()->shouldBe(true);
    }

    public function it_returns_the_given_data()
    {
        $this->beConstructedWith(['status' => 200, 'result' => '']);
        $this->getData()->shouldBe(['status' => 200, 'result' => '']);
    }
}
