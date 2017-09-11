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
use Mediapart\LaPresseLibre\Operation\AccountUpdate;
use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;
use Mediapart\LaPresseLibre\Subscription\Status as SubscriptionStatus;

class AccountUpdateTest extends TestCase
{
    public function testExecute()
    {
        $public_key = 2;
        $callback = function ($data, $isTesting) use ($public_key) {
            return [
                'IsValid' => true,
                'PartenaireID' => $public_key,
                'CodeUtilisateur' => $data['CodeUtilisateur'],
                'CodeEtat' => AccountUpdate::SUCCESS,
            ];
        };

        $endpoint = Endpoint::answer(AccountUpdate::class, $callback);
        $result = $endpoint->execute([
            'CodeUtilisateur' => 'aaaa-bbbb-2222',
            'TypeAbonnement' => SubscriptionType::MONTHLY,
            'DateSouscription' => '',
            'DateExpiration' => '',
            'Tarif' => 9.90,
            'Statut' => SubscriptionStatus::ACTIVE,
        ]);

        $this->assertEquals([
            'IsValid' => true,
            'PartenaireID' => $public_key,
            'CodeUtilisateur' => 'aaaa-bbbb-2222',
            'CodeEtat' => AccountUpdate::SUCCESS,
        ], $result);
    }
}
