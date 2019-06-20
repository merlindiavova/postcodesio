<?php

declare(strict_types=1);

namespace PostcodesIO\API\Postcode;

use Immutable\ValueObject\ValueObject;
use PostcodesIO\API\Client\Collection;
use PostcodesIO\API\Contract\CollectionInterface;

final class Data extends ValueObject
{
    /**
     * @var string
     */
    private $postcode;

    /**
     * @var int
     */
    private $quality;

    /**
     * @var int
     */
    private $eastings;

    /**
     * @var int
     */
    private $northings;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $nhsHa;

    /**
     * @var string
     */
    private $adminCounty;

    /**
     * @var string
     */
    private $adminDistrict;

    /**
     * @var string
     */
    private $adminWard;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var string
     */
    private $parliamentaryConstituency;

    /**
     * @var string
     */
    private $europeanElectoralRegion;

    /**
     * @var string
     */
    private $primaryCareTrust;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $parish;

    /**
     * @var string
     */
    private $lsoa;

    /**
     * @var string
     */
    private $msoa;

    /**
     * @var string
     */
    private $outcode;

    /**
     * @var string
     */
    private $ced;

    /**
     * @var string
     */
    private $ccg;

    /**
     * @var string
     */
    private $nuts;

    /**
     * @var Codes
     */
    private $codes;

    /**
     * @var CollectionInterface
     */
    private $query;

    public function __construct(
        string $postcode,
        int $quality,
        int $eastings,
        int $northings,
        string $country,
        string $nhsHa,
        string $adminCounty,
        string $adminDistrict,
        string $adminWard,
        float $longitude,
        float $latitude,
        string $parliamentaryConstituency,
        string $europeanElectoralRegion,
        string $primaryCareTrust,
        string $region,
        string $parish,
        string $lsoa,
        string $msoa,
        string $outcode,
        string $ced,
        string $ccg,
        string $nuts,
        Codes $codes,
        CollectionInterface $query
    ) {
        $this->postcode = $postcode;
        $this->quality = $quality;
        $this->eastings = $eastings;
        $this->northings = $northings;
        $this->country = $country;
        $this->nhsHa = $nhsHa;
        $this->adminCounty = $adminCounty;
        $this->adminDistrict = $adminDistrict;
        $this->adminWard = $adminWard;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->parliamentaryConstituency = $parliamentaryConstituency;
        $this->europeanElectoralRegion = $europeanElectoralRegion;
        $this->primaryCareTrust = $primaryCareTrust;
        $this->region = $region;
        $this->parish = $parish;
        $this->lsoa = $lsoa;
        $this->msoa = $msoa;
        $this->outcode = $outcode;
        $this->ced = $ced;
        $this->ccg = $ccg;
        $this->nuts = $nuts;
        $this->codes = $codes;
        $this->query = $query;

        parent::__construct();
    }

    public function getPostcode() : string
    {
        return $this->postcode;
    }

    public function getQuality() : int
    {
        return $this->quality;
    }

    public function getEastings() : int
    {
        return $this->eastings;
    }

    public function getNorthings() : int
    {
        return $this->northings;
    }

    public function getCountry() : string
    {
        return $this->country;
    }

    public function getNhsHa() : string
    {
        return $this->nhsHa;
    }

    public function getAdminCounty() : string
    {
        return $this->adminCounty;
    }

    public function getAdminDistrict() : string
    {
        return $this->adminDistrict;
    }

    public function getAdminWard() : string
    {
        return $this->adminWard;
    }

    public function getLongitude() : float
    {
        return $this->longitude;
    }

    public function getLatitude() : float
    {
        return $this->latitude;
    }

    public function getParliamentaryConstituency() : string
    {
        return $this->parliamentaryConstituency;
    }

    public function getEuropeanElectoralRegion() : string
    {
        return $this->europeanElectoralRegion;
    }

    public function getPrimaryCareTrust() : string
    {
        return $this->primaryCareTrust;
    }

    public function getRegion() : string
    {
        return $this->region;
    }

    public function getParish() : string
    {
        return $this->parish;
    }

    public function getLsoa() : string
    {
        return $this->lsoa;
    }

    public function getMsoa() : string
    {
        return $this->msoa;
    }

    public function getOutcode() : string
    {
        return $this->outcode;
    }

    public function getCed() : string
    {
        return $this->ced;
    }

    public function getCcg() : string
    {
        return $this->ccg;
    }

    public function getNuts() : string
    {
        return $this->nuts;
    }

    public function getCodes() : Codes
    {
        return $this->codes;
    }

    public function getQuery() : CollectionInterface
    {
        return $this->query;
    }

    public static function makeFromArray(array $data): self
    {
        return new static(
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
            ($data['codes'] instanceof Codes)
                ? $data['codes']
                : Codes::makeFromArray(
                    is_array($data['codes']) ? $data['codes'] : []
                ),
            $data['query'] ?? new Collection([])
        );
    }
}
