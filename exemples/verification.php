<?php

/**
 * Web-Service de vérification de comptes existants.
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-v%C3%A9rification-de-comptes-existants
 */

require 'vendor/autoload.php';

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Phly\Http\Response\SapiEmitter;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;
use Mediapart\LaPresseLibre\Transaction;
use Mediapart\LaPresseLibre\Endpoint;
use Mediapart\LaPresseLibre\Operation\Verification;

$public_key = 2;
$identity = new Identity('mGoMuzoX8u');
$encryption = new Encryption('UKKzV7sxiGx3uc0auKrUO2kJTT2KSCeg', '7405589013321961');
$request = ServerRequestFactory::fromGlobals();

try {
    $transaction = new Transaction($identity, $encryption, $request);
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
    $result = $transaction->process($verification);
    $status = 200;
} catch (\InvalidArgumentException $e) {
    $result = $e->getMessage();
    $status = 400;
} catch (\UnexpectedValueException $e) {
    $result = $e->getMessage();
    $status = 401;
} catch (\Exception $e) {
    $result = 'Internal Error';
    $status = 500;
} finally {
    $response = new Response(
        200 != $status ? json_encode(['error' => $result]) : $result,
        $status,
        [
            'X-PART' => (string) $public_key,
            'X-LPL' => $identity->sign($public_key),
            'X-TS' => (string) $identity->getDatetime()->getTimestamp(),
        ]
    );
}

$emitter = new SapiEmitter();
$emitter->emit($response);
