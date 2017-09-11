# `La Presse Libre` Client Library

Unofficial PHP SDK for the project [La Presse Libre](https://github.com/NextINpact/LaPresseLibreSDK). The difference with the official package offered by NextINpact is compatibility with PSR4, PSR7 and php7 environment.

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

Detailed examples for each endpoints are available :

- [exemples/verification.php](exemples/verification.php)
- [exemples/account-creation.php](exemples/account-creation.php)
- [exemples/account-update.php](exemples/account-update.php)
- [exemples/register.php](exemples/register.php)

## Installation

Simply install this package with [Composer](http://getcomposer.org/).

```bash
composer require mediapart/lapresselibre
```

## Read More

- Official `La Presse Libre` [documentation](https://github.com/NextINpact/LaPresseLibreSDK/wiki/) (fr).