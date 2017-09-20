<?php

/**
 * Web-Service de vérification de comptes existants.
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-v%C3%A9rification-de-comptes-existants
 */

require 'bootstrap.php';

use Mediapart\LaPresseLibre\Operation\Verification;
use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;

$handle(Verification::class, function($data) use ($public_key) {
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
});
