# `La Presse Libre` Client Library

[![Build Status](https://secure.travis-ci.org/mediapart/lapresselibre.svg?branch=master)](http://travis-ci.org/mediapart/lapresselibre) [![Code Coverage](https://codecov.io/gh/mediapart/lapresselibre/branch/master/graph/badge.svg)](https://scrutinizer-ci.com/g/mediapart/lapresselibre) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mediapart/lapresselibre/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mediapart/lapresselibre) [![Total Downloads](https://poser.pugx.org/mediapart/lapresselibre/downloads.png)](https://packagist.org/packages/mediapart/lapresselibre) [![Latest Stable Version](https://poser.pugx.org/mediapart/lapresselibre/v/stable.png)](https://packagist.org/packages/mediapart/lapresselibre)

Unofficial PHP SDK for the project [La Presse Libre](https://github.com/NextINpact/LaPresseLibreSDK). The difference with [the official package offered by NextINpact](https://github.com/NextINpact/LaPresseLibreSDK/tree/master/php) is compatibility with PSR4, PSR7 and php7 environment.

## Usage

```php
$account_always_exists = function ($data, $is_testing) use ($public_key) {
    $now = new DateTime('next year');

    return [
        'Mail' => $data['Mail'],
        'CodeUtilisateur' => $data['CodeUtilisateur'],
        'TypeAbonnement' => SubscriptionType::MONTHLY,
        'DateExpiration' => $now->format("Y-m-d\TH:i:sO"),
        'DateSouscription' => $now->format("Y-m-d\TH:i:sO"),
        'AccountExist' => true,
        'PartenaireID' => $public_key,
    ];
};
$verification = Endpoint::answer(Verification::class, $account_always_exists);
```

Detailed examples for each endpoints are available in [examples/](examples/).

## Installation

Simply install this package with [Composer](http://getcomposer.org/).

```bash
composer require mediapart/lapresselibre
```

## Read More

- Official `La Presse Libre` [documentation](https://github.com/NextINpact/LaPresseLibreSDK/wiki/) (fr).