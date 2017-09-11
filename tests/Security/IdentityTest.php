<?php

/**
 * This file is part of the Mediapart LaPresseLibre Library.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\LaPresseLibre\Security\Tests;

use PHPUnit\Framework\TestCase;
use Mediapart\LaPresseLibre\Security\Identity;

class IdentityTest extends TestCase
{
    public function testSign()
    {
        $identity = new Identity('secretkey');
        $signature = $identity->sign('publickey', 1500000000);

        $this->assertEquals('b031e33b78f12e5246b64bd6a17935372ded5bf3', $signature);
        $this->assertInstanceOf(\Datetime::class, $identity->getDatetime());
    }
}
