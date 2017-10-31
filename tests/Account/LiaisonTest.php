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
use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Account\Liaison;
use Mediapart\LaPresseLibre\Account\Account;
use Mediapart\LaPresseLibre\Account\Repository;

class LiaisonTest extends TestCase
{
    public function testSuccessWithExistingAccount()
    {
        $public_key = "2";
        $code = '99f104e8-2fa3-4a77-1664-5bac75fb668d';
        $encryption = $this->createMock(Encryption::class);
        $repository = $this->createMock(Repository::class);
        $logguedAccount = new Account('username@domain.tld', $code);
        $repository
            ->method('find')
            ->with($code)
            ->willReturn(clone $logguedAccount)
        ;
        $encryption
            ->method('decrypt')
            ->with('received data')
            ->willReturn($code)
        ;
        $encryption
            ->method('encrypt')
            ->with(['Guid' => $code, 'statut' => Liaison::STATUS_SUCCESS])
            ->willReturn('response data')
        ;

        $liaison = new Liaison($encryption, $repository, $public_key);
        $link = $liaison->generateUrl('received data', $logguedAccount);

        $this->assertEquals('https://beta.lapresselibre.fr/manage/link-result?lpl=response%20data&part=2', $link);
    }

    public function testConflict()
    {
        $public_key = "2";
        $code = '99f104e8-2fa3-4a77-1664-5bac75fb668d';
        $encryption = $this->createMock(Encryption::class);
        $repository = $this->createMock(Repository::class);
        $logguedAccount = new Account('username@domain.tld');
        $conflictedAccount = new Account('othername@domain.tld', $code);
        $repository
            ->method('find')
            ->with($code)
            ->willReturn($conflictedAccount)
        ;
        $encryption
            ->method('decrypt')
            ->with('received data')
            ->willReturn($code)
        ;
        $encryption
            ->method('encrypt')
            ->with(['Guid' => $code, 'statut' => Liaison::STATUS_CONFLICT])
            ->willReturn('response data')
        ;

        $liaison = new Liaison($encryption, $repository, $public_key);
        $link = $liaison->generateUrl('received data', $logguedAccount);

        $this->assertEquals('https://beta.lapresselibre.fr/manage/link-result?lpl=response%20data&part=2', $link);
    }

    public function testSuccessWithSavingNewLinkedAccount()
    {
        $public_key = "2";
        $code = '99f104e8-2fa3-4a77-1664-5bac75fb668d';
        $encryption = $this->createMock(Encryption::class);
        $repository = $this->createMock(Repository::class);
        $logguedAccount = new Account('username@domain.tld');
        $repository
            ->expects($this->once())
            ->method('save')
            ->with(new Account($logguedAccount->getEmail(), $code))
        ;
        $encryption
            ->method('decrypt')
            ->with('received data')
            ->willReturn($code)
        ;
        $encryption
            ->method('encrypt')
            ->with(['Guid' => $code, 'statut' => Liaison::STATUS_SUCCESS])
            ->willReturn('response data')
        ;

        $liaison = new Liaison($encryption, $repository, $public_key);
        $link = $liaison->generateUrl('received data', $logguedAccount);

        $this->assertEquals('https://beta.lapresselibre.fr/manage/link-result?lpl=response%20data&part=2', $link);
    }

    public function testErrorWhenSavingNewLinkedAccount()
    {
        $public_key = "2";
        $code = '99f104e8-2fa3-4a77-1664-5bac75fb668d';
        $encryption = $this->createMock(Encryption::class);
        $repository = $this->createMock(Repository::class);
        $logguedAccount = new Account('username@domain.tld');
        $repository
            ->expects($this->once())
            ->method('save')
            ->with(new Account($logguedAccount->getEmail(), $code))
            ->will($this->throwException(new \Exception));
        ;
        $encryption
            ->method('decrypt')
            ->with('received data')
            ->willReturn($code)
        ;
        $encryption
            ->method('encrypt')
            ->with(['Guid' => $code, 'statut' => Liaison::STATUS_FAILURE])
            ->willReturn('response data')
        ;

        $liaison = new Liaison($encryption, $repository, $public_key);
        $link = $liaison->generateUrl('received data', $logguedAccount);

        $this->assertEquals('https://beta.lapresselibre.fr/manage/link-result?lpl=response%20data&part=2', $link);
    }
}
