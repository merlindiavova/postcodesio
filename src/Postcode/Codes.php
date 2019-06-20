<?php

declare(strict_types=1);

namespace PostcodesIO\API\Postcode;

use Immutable\ValueObject\ValueObject;
use Psr\Http\Message\ResponseInterface;
use PostcodesIO\API\Contract\ClientInterface;
use PostcodesIO\API\Client\Response as ClientResponse;

/**
 * IDs or Codes associated with the postcode.
 *
 * Typically, these are a 9 character code known as an ONS Code or GSS Code.
 * This is currently only available for districts, parishes, counties,
 * CCGs, NUTS and wards
 */
final class Codes extends ValueObject
{
    /**
     * @var string
     */
    private $adminDistrict;

    /**
     * @var string
     */
    private $adminCounty;

    /**
     * @var string
     */
    private $adminWard;

    /**
     * @var string
     */
    private $parish;

    /**
     * @var string
     */
    private $parliamentaryConstituency;

    /**
     * @var string
     */
    private $ccg;

    /**
     * @var string
     */
    private $ced;

    /**
     * @var string
     */
    private $nuts;

    public function __construct(
        string $adminDistrict,
        string $adminCounty,
        string $adminWard,
        string $parish,
        string $parliamentaryConstituency,
        string $ccg,
        string $ced,
        string $nuts
    ) {
        $this->ccg = $ccg;
        $this->ced = $ced;
        $this->nuts = $nuts;
        $this->parish = $parish;
        $this->adminWard = $adminWard;
        $this->adminCounty = $adminCounty;
        $this->adminDistrict = $adminDistrict;
        $this->parliamentaryConstituency = $parliamentaryConstituency;

        parent::__construct();
    }

    public function getAdminDistrict() : string
    {
        return $this->adminDistrict;
    }

    public function getAdminCounty() : string
    {
        return $this->adminCounty;
    }

    public function getAdminWard() : string
    {
        return $this->adminWard;
    }

    public function getParish() : string
    {
        return $this->parish;
    }

    public function getParliamentaryConstituency() : string
    {
        return $this->parliamentaryConstituency;
    }

    public function getCcg() : string
    {
        return $this->ccg;
    }

    public function getCed() : string
    {
        return $this->ced;
    }

    public function getNuts() : string
    {
        return $this->nuts;
    }

    public static function makeFromArray(array $data): self
    {
        return new static(
            $data['admin_district'] ?? '',
            $data['admin_county'] ?? '',
            $data['admin_ward'] ?? '',
            $data['parish'] ?? '',
            $data['parliamentary_constituency'] ?? '',
            $data['ccg'] ?? '',
            $data['ced'] ?? '',
            $data['nuts'] ?? ''
        );
    }
}
