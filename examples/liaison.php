<?php

/**
 * Liaison de compte utilisateur par redirection.
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Liaison-de-compte-utilisateur-par-redirection
 */

require 'bootstrap.php';

use Mediapart\LaPresseLibre\Account\Liaison;
use Mediapart\LaPresseLibre\Account\Account;
use Mediapart\LaPresseLibre\Account\Repository;

class MemoryRepository implements Repository
{
	private $accounts = [];
	public function __construct($accounts)
	{
		array_map([$this, 'save'], $accounts);
	}
	public function find($code)
	{
		return $this->accounts[$code];
	}
	public function save(Account $account)
	{
		$this->accounts[$account->getCode()] = $account;
	}
}

$repository = new MemoryRepository([
	new Account('test1@domain.tld', '99f104e8-2fa3-4a77-1664-5bac75fb668d'),
	new Account('test2@domain.tld', '68b3c837-c7f4-1b54-2efa-1c5cc2945c3f'),
]);
$logguedAccount = new Account('test3@domain.tld', '7f75e972-d5c7-b0c5-1a1b-9d5a582cbd27');

$liaison = new Liaison($encryption, $repository, $public_key);
$redirection = $liaison->generateUrl($_GET['lpluser'], $logguedAccount);

header('Location: '.$redirection);
