<?php

/**
 * This file is part of the Mediapart LaPresseLibre Library.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\LaPresseLibre\Tests;

use PHPUnit\Framework\TestCase;
use Mediapart\LaPresseLibre\Endpoint;
use Mediapart\LaPresseLibre\Subscription\Status;
use Mediapart\LaPresseLibre\Operation\AccountCreation;

class AccountCreationTest extends TestCase
{
    public function testExecute()
    {
        $public_key = 2;
        $callback = function ($data, $isTesting) use ($public_key) {
            return [
                'IsValid' => true,
                'PartenaireID' => $public_key,
                'CodeUtilisateur' => $data['CodeUtilisateur'],
                'CodeEtat' => AccountCreation::SUCCESS,
            ];
        };

        $endpoint = Endpoint::answer(AccountCreation::class, $callback);
        $result = $endpoint->execute([
            'Pseudo' => 'pseudo',
            'Mail' => 'pseudo@domain.tld',
            'Password' => 'pass',
            'TypeAbonnement' => 'mensuel',
            'DateSouscription' => '',
            'DateExpiration' => '',
            'Tarif' => 9.90,
            'CodeUtilisateur' => 'aaaa-bbbb-2222',
            'Statut' => Status::ACTIVE,
        ]);

        $this->assertEquals([
            'IsValid' => true,
            'PartenaireID' => $public_key,
            'CodeUtilisateur' => 'aaaa-bbbb-2222',
            'CodeEtat' => AccountCreation::SUCCESS,
        ], $result);
    }
}
