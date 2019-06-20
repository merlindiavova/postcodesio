# Postcodes.io API

[![Build Status](https://travis-ci.org/merlindiavova/postcodesio.svg?branch=master)](https://travis-ci.org/merlindiavova/postcodesio) [![Build Status](https://scrutinizer-ci.com/g/merlindiavova/postcodesio/badges/build.png?b=master)](https://scrutinizer-ci.com/g/merlindiavova/postcodesio/build-status/master) [![Latest Stable Version](https://poser.pugx.org/merlindiavova/postcodesio/version)](https://packagist.org/packages/merlindiavova/postcodesio) [![License](https://poser.pugx.org/merlindiavova/postcodesio/license)](https://packagist.org/packages/merlindiavova/postcodesio)

PHP client to consume the [Postcodes.io](https://postcodes.io/) API.

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install this library. Current supported PHP versions are 7.1-7.3.

To install this libary please run the following command:

```bash
$ composer require merlindiavova/postcodesio
```

## Example
The example below uses [sunrise/http-client-curl](https://github.com/sunrise-php/http-client-curl) as the [PSR-18](https://www.php-fig.org/psr/psr-18/) implementation and [Nyholm/psr7](https://github.com/Nyholm/psr7) as the [PSR-7](https://www.php-fig.org/psr/psr-7/) implementation.

```php
<?php

declare(strict_types=1);

use PostcodesIO\API\Client;
use PostcodesIO\API\Client\ResponseCheck;
use PostcodesIO\API\Factory\Psr17Factory;
use Sunrise\Http\Client\Curl\Client as SunClient;
use Nyholm\Psr7\Factory\Psr17Factory as NyholmPsr17Factory;

require __DIR__ . '/vendor/autoload.php';

$psr7Implementation = new NyholmPsr17Factory();

$postcodesIoClient = new Client(
    new SunClient($psr7Implementation),
    new Psr17Factory(
        $psr7Implementation,
        $psr7Implementation,
        $psr7Implementation,
        $psr7Implementation
    )
);

$response = $postcodesIoClient->getPostcodeClient()->fetch('NW10 4DG');

$postcode = $response->getFirstResult(); // PostcodesIO\API\Postcode\Data

echo $postcode->getAdminWard() . ', ' . $postcode->getAdminDistrict(); // Harlesden, Brent
```

### Choose a PSR-7 implementation
This library does not come with any [PSR-7](https://www.php-fig.org/psr/psr-7/) implementations. You are free to use any implementation that best suits your circumstance. Below are a few notable ones:

- [Slim-Psr7](https://github.com/slimphp/Slim-Psr7) - Install using `composer require slim/psr7`. This is the Slim Framework projects [PSR-7](https://www.php-fig.org/psr/psr-7/) implementation.
- [Nyholm/psr7](https://github.com/Nyholm/psr7) - Install using `composer require nyholm/psr7`. This is the fastest, strictest and most lightweight implementation at the moment.
- [Guzzle/psr7](https://github.com/guzzle/psr7) - Install using `composer require guzzlehttp/psr7`. This is the implementation used by the Guzzle Client. It is not as strict but adds some nice functionality for Streams and file handling. It is the second fastest implementation but is a bit bulkier.
- [zend-diactoros](https://github.com/zendframework/zend-diactoros) - Install using `composer require zendframework/zend-diactoros`. This is the Zend implementation. It is the slowest implementation of the four.

> Notable psr7-implementations taken [https://github.com/slimphp/Slim/tree/4.x](https://github.com/slimphp/Slim/tree/4.x)

### Choose a PSR-18 implementation
This package does not come with a [PSR-18](https://www.php-fig.org/psr/psr-18/) HTTP Client implementation. You are free to use any implementation that best suits your circumstance. Below are a few options:

- [sunrise/http-client-curl](https://github.com/sunrise-php/http-client-curl) - Install using `composer require sunrise/http-client-curl`. Super light weight Curl Client
- [kriswallsmith/buzz](https://github.com/kriswallsmith/Buzz)  - Install using `composer require kriswallsmith/buzz`. Another light weight client
- [php-http/guzzle6-adapter](https://github.com/php-http/guzzle6-adapter) - Install using `composer require php-http/guzzle6-adapter`. Bulky Guzzle 6 HTTP Adapter.

## Api Clients

| Endpoint | Code to get endpoint |
| -------- | -------------------- |
| https://api.postcodes.io/postcodes | $postcodesIoClient->getPostcodeClient() |
| https://api.postcodes.io/outcodes | $postcodesIoClient->getOutcodeClient() |
| https://api.postcodes.io/places | $postcodesIoClient->getPlaceClient() |
| https://api.postcodes.io/terminated_postcodes | $postcodesIoClient->getPostcodeClient() |

### Postcodes

```php
<?php
// ... 
// Returns the postcode api client
$postcodesClient = $postcodesIoClient->getPostcodeClient();

// Fetch a postcode
$response = $postcodesClient->fetch('HA0 2TF'); // Returns a Postcode\Response object
$postcode = $response->getFirstResult(); // Returns Postcode\Data object

// Fetch many postcodes
$response = $postcodesClient->fetchByBulk(['HA0 2TF', 'SE1 2UP']); // Returns a Postcode\Response object
$postcodes = $response->getResult(); // Returns Client\Collection of Postcode\Data objects

// Fetch Reverse Geocode
$response = $postcodesClient->fetchByReverseGeocode(-0.076579, 51.503378); // Returns a Postcode\Response object
$postcodes = $response->getResult(); // Returns Client\Collection of Postcode\Data objects

// you add an array of optional query parameters as the third argument
$queryParams = ['limit' => 10, 'radius' => '1000', 'wideSearch' => true];
$response = $postcodesClient->fetchByReverseGeocode(-0.076579, 51.503378, $queryParams);
```
