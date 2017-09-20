<?php

/**
 * Web service de création de comptes.
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-cr%C3%A9ation-de-comptes
 */

require 'bootstrap.php';

use Mediapart\LaPresseLibre\Operation\AccountCreation;

$handle(AccountCreation::class, function ($data, $is_testing) use ($public_key) {
    return [
        'IsValid' => true,
        'PartenaireID' => $public_key,
        'CodeUtilisateur' => $data['CodeUtilisateur'],
        'CodeEtat' => AccountCreation::SUCCESS,
    ];
});
