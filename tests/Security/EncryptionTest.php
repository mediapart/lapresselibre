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
    public function testEncryption()
    {
        $string = 'lorem ipsum dolor';
        $encryption = new Encryption('passphrase', '8265408651542848', 0);

        $encrypted = $encryption->encrypt($string);
        $decrypted = $encryption->decrypt($encrypted);

        $this->assertEquals('UVZVTXBlNnBsSy9Ea2lsai8zRjZreElvbHAxeW0rVm1rcmRzNi9nQ2lKYz0=', $encrypted);
        $this->assertEquals($string, $decrypted);
    }

    public function testNotJsonStringDecryption()
    {
    	$encrypted = 'VHZoeHhsdjgwV3RsODVVcEd5ak1MaWc3UlJFRnF6Ly9IemtWSUlWcjJlMD0=';

        $encryption = new Encryption('passphrase', '8265408651542848', 0);
        $decrypted = $encryption->decrypt($encrypted);

        $this->assertEquals('lorem ipsum dolor', $decrypted);
    }

    public function testIv()
    {
    	$string = 'loremp ipsum dolor';

        $encryption = new Encryption('passphrase', null, 0);
        $encrypted = $encryption->encrypt($string);

        $this->assertNotEquals('', $encrypted);
        $this->assertNotEquals($string, $encrypted);
    }
}
