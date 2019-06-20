<?php

declare(strict_types=1);

namespace spec\PostcodesIO\API\Client;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use PostcodesIO\API\Exception;
use PostcodesIO\API\Client\ResponseCheck;

class ResponseCheckSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ResponseCheck::class);
    }

    public function it_returns_null_for_successful_response()
    {
        $this->__invoke($this->makeValidData(), 200)->shouldBe(null);
    }

    public function it_throws_server_exception_for_codes_5xx()
    {
        $message = 'A bad server error';
        $this->shouldThrow(new Exception\Server($message, 500))
            ->during('__invoke', [$this->makeError($message, 500), 500]);
    }

    public function it_throws_validation_exception_for_invalid_responses()
    {
        $message = 'Invalid postcode';
        $this->shouldThrow(new Exception\Validation($message, 400))
            ->during('__invoke', [$this->makeError($message), 404]);
    }

    public function it_throws_not_found_exception_for_not_found_responses()
    {
        $message = 'Postcode not found';
        $this->shouldThrow(new Exception\NotFound($message, 404))
            ->during('__invoke', [$this->makeError($message, 404), 404]);
    }

    public function it_throws_not_found_exception_for_invalid_resources()
    {
        $message = 'Resource not found';
        $this->shouldThrow(new Exception\ResourceNotFound($message, 404))
            ->during('__invoke', [$this->makeError($message, 404), 404]);
    }

    private function makeError(
        string $message,
        int $statusCode = 404
    ): array {
        return [
            'status' => $statusCode,
            'error' => $message
        ];
    }

    private function makeValidData(): array
    {
        return [
            'status' => 200,
            'result' => []
        ];
    }
}
