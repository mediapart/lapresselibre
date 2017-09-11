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
use Mediapart\LaPresseLibre\Security\Encryption;

class EncryptionTest extends TestCase
{
    public function testEncrypt()
    {
        $string = 'lorem ipsum dolor';
        $encryption = new Encryption('passphrase');

        $encrypted = $encryption->encrypt($string);
        $decrypted = $encryption->decrypt($string);

        $this->assertEquals($encrypted, $decrypted);
    }
}
