<?php

declare(strict_types=1);

namespace PostcodesIO\API\Place;

use Immutable\ValueObject\ValueObject;

final class Data extends ValueObject
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name1;

    /**
     * @var string
     */
    private $name1Lang;

    /**
     * @var string
     */
    private $name2;

    /**
     * @var string
     */
    private $name2Lang;

    /**
     * @var string
     */
    private $localType;

    /**
     * @var string
     */
    private $outcode;

    /**
     * @var string
     */
    private $countyUnitary;

    /**
     * @var string
     */
    private $countyUnitaryType;

    /**
     * @var string
     */
    private $districtBorough;

    /**
     * @var string
     */
    private $districtBoroughType;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $country;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var int
     */
    private $eastings;

    /**
     * @var int
     */
    private $northings;

    /**
     * @var int
     */
    private $minEastings;

    /**
     * @var int
     */
    private $minNorthings;

    /**
     * @var int
     */
    private $maxEastings;

    /**
     * @var int
     */
    private $maxNorthings;


    public function __construct(
        string $code,
        string $name1,
        string $name1Lang,
        string $name2,
        string $name2Lang,
        string $localType,
        string $outcode,
        string $countyUnitary,
        string $countyUnitaryType,
        string $districtBorough,
        string $districtBoroughType,
        string $region,
        string $country,
        float $longitude,
        float $latitude,
        int $eastings,
        int $northings,
        int $minEastings,
        int $minNorthings,
        int $maxEastings,
        int $maxNorthings
    ) {
        $this->code = $code;
        $this->name1 = $name1;
        $this->name1Lang = $name1Lang;
        $this->name2 = $name2;
        $this->name2Lang = $name2Lang;
        $this->localType = $localType;
        $this->outcode = $outcode;
        $this->countyUnitary = $countyUnitary;
        $this->countyUnitaryType = $countyUnitaryType;
        $this->districtBorough = $districtBorough;
        $this->districtBoroughType = $districtBoroughType;
        $this->region = $region;
        $this->country = $country;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->eastings = $eastings;
        $this->northings = $northings;
        $this->minEastings = $minEastings;
        $this->minNorthings = $minNorthings;
        $this->maxEastings = $maxEastings;
        $this->maxNorthings = $maxNorthings;

        parent::__construct();
    }

    public function getCode() : string
    {
        return $this->code;
    }

    public function getName1() : string
    {
        return $this->name1;
    }

    public function getName1Lang() : string
    {
        return $this->name1Lang;
    }

    public function getName2() : string
    {
        return $this->name2;
    }

    public function getName2Lang() : string
    {
        return $this->name2Lang;
    }

    public function getLocalType() : string
    {
        return $this->localType;
    }

    public function getOutcode() : string
    {
        return $this->outcode;
    }

    public function getCountyUnitary() : string
    {
        return $this->countyUnitary;
    }

    public function getCountyUnitaryType() : string
    {
        return $this->countyUnitaryType;
    }

    public function getDistrictBorough() : string
    {
        return $this->districtBorough;
    }

    public function getDistrictBoroughType() : string
    {
        return $this->districtBoroughType;
    }

    public function getRegion() : string
    {
        return $this->region;
    }

    public function getCountry() : string
    {
        return $this->country;
    }

    public function getLongitude() : float
    {
        return $this->longitude;
    }

    public function getLatitude() : float
    {
        return $this->latitude;
    }

    public function getEastings() : int
    {
        return $this->eastings;
    }

    public function getNorthings() : int
    {
        return $this->northings;
    }

    public function getMinEastings() : int
    {
        return $this->minEastings;
    }

    public function getMinNorthings() : int
    {
        return $this->minNorthings;
    }

    public function getMaxEastings() : int
    {
        return $this->maxEastings;
    }

    public function getMaxNorthings() : int
    {
        return $this->maxNorthings;
    }

    public static function makeFromArray(array $data) : self
    {
        return new static(
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
}
