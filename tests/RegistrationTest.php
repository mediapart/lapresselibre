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
use Mediapart\LaPresseLibre\Registration;
use Mediapart\LaPresseLibre\Security\Encryption;

class RegistrationTest extends TestCase
{
    public function testGenerateLink()
    {
        $encryption = $this->createMock(Encryption::class);

        $encryption
            ->method('encrypt')
            ->with([
                'Email' => 'user@domain.tld',
                'Pseudo' => 'username',
                'Guid' => null,
            ])
            ->willReturn('foobar')
        ;

        $registration = new Registration(1, $encryption);
        $link = $registration->generateLink('user@domain.tld', 'username');

        $this->assertEquals(Registration::LINK_ROUTE.'?user=foobar&partId=1', $link);
    }
}
