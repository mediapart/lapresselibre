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
use Mediapart\LaPresseLibre\Account\Account;

class AccountTest extends TestCase
{
    public function testAccountWithoutCode()
    {
        $email = 'username@domain.tld';

        $account = new Account($email);

        $this->assertEquals($email, $account->getEmail());
        $this->assertNull($account->getCode());
    }

    public function testAccountWithCode()
    {
        $email = 'username@domain.tld';
        $lplCode = '68b3c837-c7f4-1b54-2efa-1c5cc2945c3f';

        $account = new Account($email, $lplCode);

        $this->assertEquals($email, $account->getEmail());
        $this->assertEquals($lplCode, $account->getCode());
    }
}
