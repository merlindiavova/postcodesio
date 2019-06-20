<?php

namespace spec\PostcodesIO\API\Client;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Client\Error;
use PostcodesIO\API\Client\Response;
use PostcodesIO\API\Exception;
use PostcodesIO\API\Contract\ResponseInterface;

class ErrorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'status' => 404,
            'error' => 'An error message'
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Error::class);
        $this->shouldHaveType(Response::class);
        $this->shouldHaveType(ResponseInterface::class);
    }

    public function it_throws_exception_if_expected_keys_are_missing()
    {
        $data = [
            'statuses' => 404,
            'errors' => 'An error message'
        ];

        $message = 'Missing expected response keys: status, error';

        $this->shouldThrow(new \RuntimeException($message))
            ->during('__construct', [$data]);
    }

    public function it_should_return_true_error_checks()
    {
        $this->hasErrors()->shouldBe(true);
        $this->isSuccessful()->shouldBe(false);
    }

    public function it_should_return_int_status_code()
    {
        $this->beConstructedWith(['status' => 404, 'error' => '']);
        $this->getCode()->shouldBeInt();
        $this->getCode()->shouldEqual(404);
    }

    public function it_should_return_string_error_messages()
    {
        $message = 'Some error messages';
        $this->beConstructedWith(['status' => 500, 'error' => $message]);

        $this->getMessage()->shouldBeString();
        $this->getMessage()->shouldEqual($message);
    }

    public function it_should_throw_an_exception_when_trying_to_get_result()
    {
        $this->shouldThrow(new Exception\RuntimeException(
            'Error Response do not contain any results.'
        ))->during('getResult');
    }
}
