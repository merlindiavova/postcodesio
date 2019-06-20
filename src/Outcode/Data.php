<?php

declare(strict_types=1);

namespace PostcodesIO\API\Outcode;

use Immutable\ValueObject\ValueObject;
use PostcodesIO\API\Client\Collection;
use PostcodesIO\API\Contract\CollectionInterface;

final class Data extends ValueObject
{
    /**
     * @var string
     */
    private $outcode;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var ?int
     */
    private $northings;

    /**
     * @var ?int
     */
    private $eastings;

    /**
     * @var CollectionInterface
     */
    private $adminDistrict;

    /**
     * @var CollectionInterface
     */
    private $parish;

    /**
     * @var CollectionInterface
     */
    private $adminCounty;

    /**
     * @var CollectionInterface
     */
    private $adminWard;

    /**
     * @var CollectionInterface
     */
    private $country;

    public function __construct(
        string $outcode,
        float $longitude,
        float $latitude,
        CollectionInterface $adminDistrict,
        CollectionInterface $parish,
        CollectionInterface $adminCounty,
        CollectionInterface $adminWard,
        CollectionInterface $country,
        int $northings = null,
        int $eastings = null
    ) {

        $this->outcode = $outcode;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->northings = $northings;
        $this->eastings = $eastings;
        $this->adminDistrict = $adminDistrict;
        $this->parish = $parish;
        $this->adminCounty = $adminCounty;
        $this->adminWard = $adminWard;
        $this->country = $country;

        parent::__construct();
    }

    public static function makeFromArray(array $data) : self
    {
        return new static(
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

    public function getOutcode() : string
    {
        return $this->outcode;
    }

    public function getLongitude() : float
    {
        return $this->longitude;
    }

    public function getLatitude() : float
    {
        return $this->latitude;
    }

    public function getNorthings() : ?int
    {
        return $this->northings;
    }

    public function getEastings() : ?int
    {
        return $this->eastings;
    }

    public function getAdminDistrict() : CollectionInterface
    {
        return $this->adminDistrict;
    }

    public function getParish() : CollectionInterface
    {
        return $this->parish;
    }

    public function getAdminCounty() : CollectionInterface
    {
        return $this->adminCounty;
    }

    public function getAdminWard() : CollectionInterface
    {
        return $this->adminWard;
    }

    public function getCountry() : CollectionInterface
    {
        return $this->country;
    }
}
