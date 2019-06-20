<?php

declare(strict_types=1);

namespace PostcodesIO\API\Postcode;

use Immutable\ValueObject\ValueObject;
use Psr\Http\Message\ResponseInterface;
use PostcodesIO\API\Client\Collection;
use PostcodesIO\API\Contract\ClientInterface;
use PostcodesIO\API\Contract\CollectionInterface;
use PostcodesIO\API\Client\Response as ClientResponse;

final class TerminatedData extends ValueObject
{
    /**
     * @var string
     */
    private $postcode;

    /**
     * @var int
     */
    private $yearTerminated;

    /**
     * @var int
     */
    private $monthTerminated;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    public function __construct(
        string $postcode,
        int $yearTerminated,
        int $monthTerminated,
        float $longitude,
        float $latitude
    ) {
        $this->postcode = $postcode;
        $this->yearTerminated = $yearTerminated;
        $this->monthTerminated = $monthTerminated;
        $this->longitude = $longitude;
        $this->latitude = $latitude;

        parent::__construct();
    }

    public function getPostcode() : string
    {
        return $this->postcode;
    }

    public function getYearTerminated() : int
    {
        return $this->yearTerminated;
    }

    public function getMonthTerminated() : int
    {
        return $this->monthTerminated;
    }

    public function getLongitude() : float
    {
        return $this->longitude;
    }

    public function getLatitude() : float
    {
        return $this->latitude;
    }

    public static function makeFromArray(array $data) : self
    {
        return new static(
            $data['postcode'] ?? '',
            $data['year_terminated'] ?? 0,
            $data['month_terminated'] ?? 0,
            $data['longitude'] ?? '',
            $data['latitude'] ?? ''
        );
    }
}
