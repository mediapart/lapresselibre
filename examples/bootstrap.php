<?php

require '../vendor/autoload.php';

use Zend\Diactoros\ServerRequestFactory;                                                                    
use Zend\Diactoros\Response;                                                                                           
use Zend\Diactoros\Response\SapiEmitter;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;
use Mediapart\LaPresseLibre\Transaction;                                         
use Mediapart\LaPresseLibre\Endpoint;                                                                      

/*
 Configuration :

 download the file : `https://partenaire.lapresselibre.fr/gestion/credentials`
 into `credentials.txt`
*/
$config = json_decode(file_get_contents('credentials.txt'));

$public_key = $config->CodePartenaire;
$identity = new Identity($config->secret);
$encryption = new Encryption($config->Aes, $config->Iv);

/**
 * Handle an api endpoint of La Presse Libre.
 *
 * @param string $operation
 * @param callable $callback
 */
$handle = function($operation, $callback) use ($identity, $encryption, $public_key)
{
	try {
		$request = ServerRequestFactory::fromGlobals();
	    $transaction = new Transaction($identity, $encryption, $request);
	    $endpoint = Endpoint::answer($operation, $callback);
	    $result = $transaction->process($endpoint);
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
	    $response = (new Response())
	        ->withStatus($status)
	        ->withHeader('X-PART', (string) $public_key)
	        ->withHeader('X-LPL', $identity->sign($public_key))
	        ->withHeader('X-TS', (string) $identity->getDatetime()->getTimestamp())
	    ;
	    $response->getBody()->write(200 != $status ? json_encode(['error' => $result]) : $result);     
	}

	$emitter = new SapiEmitter();
	$emitter->emit($response);
};
