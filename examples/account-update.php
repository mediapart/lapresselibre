<?php

/**
 * Web service de mise à jour de comptes.
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-mise-%C3%A0-jour-de-comptes
 */

require 'bootstrap.php';

use Mediapart\LaPresseLibre\Operation\AccountUpdate;

$handle(AccountUpdate::class, function($data) use ($public_key) {
    return [
        'IsValid' => true,
        'PartenaireID' => $public_key,
        'CodeUtilisateur' => $data['CodeUtilisateur'],
        'CodeEtat' => AccountUpdate::SUCCESS,
    ];
});
