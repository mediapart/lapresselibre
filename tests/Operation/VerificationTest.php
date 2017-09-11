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
use Mediapart\LaPresseLibre\Operation\Verification;

class VerificationTest extends TestCase
{
    public function testExecute()
    {
        $userMail = 'user@domain.tld';
        $userCode = '42';

        $callback = function ($data, $isTesting) {
            return [
                'Mail' => $data['Mail'],
                'CodeUtilisateur' => $data['CodeUtilisateur'],
                'AccountExist' => false,
                'PartenaireID' => 1,
            ];
        };

        $endpoint = Endpoint::answer(Verification::class, $callback);
        $result = $endpoint->execute(['Mail' => $userMail, 'CodeUtilisateur' => $userCode]);

        $this->assertEquals([
            'TypeAbonnement' => null,
            'DateExpiration' => null,
            'DateSouscription' => null,
            'Mail' => $userMail,
            'CodeUtilisateur' => $userCode,
            'AccountExist' => false,
            'PartenaireID' => 1,
        ], $result);
    }
}
