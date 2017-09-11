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
use Psr\Http\Message\UriInterface as Uri;
use Psr\Http\Message\RequestInterface as Request;
use Mediapart\LaPresseLibre\Transaction;
use Mediapart\LaPresseLibre\Endpoint;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\LaPresseLibre\Security\Encryption;

class TransactionTest extends TestCase
{
    public function testWithInvalidSignature()
    {
        $identity = $this->createMock(Identity::class);
        $encryption = $this->createMock(Encryption::class);
        $request = $this->createMock(Request::class);

        $request
            ->method('hasHeader')
            ->will($this->returnValueMap([
                ['X-PART', true],
                ['X-TS', true],
                ['X-LPL', true],
                ['X-CTX', false],
            ]))
        ;
        $request
            ->method('getHeaderLine')
            ->will($this->returnValueMap([
                ['X-PART', 'partnerkey'],
                ['X-TS', time()],
                ['X-LPL', 'signatureA'],
            ]))
        ;

        $identity
            ->method('sign')
            ->willReturn('signatureB')
        ;

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Request signed by signatureA but expected signatureB');

        $transaction = new Transaction($identity, $encryption, $request);
    }

    public function testMissingRequiredHeader()
    {
        $identity = $this->createMock(Identity::class);
        $encryption = $this->createMock(Encryption::class);
        $request = $this->createMock(Request::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing header X-PART');

        $transaction = new Transaction($identity, $encryption, $request);
    }

    public function testIsTesting()
    {
        $identity = $this->createMock(Identity::class);
        $encryption = $this->createMock(Encryption::class);
        $request = $this->createMock(Request::class);

        $request
            ->method('hasHeader')
            ->will($this->returnValueMap([
                ['X-PART', true],
                ['X-TS', true],
                ['X-LPL', true],
                ['X-CTX', false],
            ]))
        ;
        $request
            ->method('getHeaderLine')
            ->will($this->returnValueMap([
                ['X-PART', 'partnerkey'],
                ['X-TS', time()],
                ['X-LPL', 'signatureA'],
            ]))
        ;

        $identity
            ->method('sign')
            ->willReturn('signatureA')
        ;

        $transaction = new Transaction($identity, $encryption, $request);

        $this->assertFalse($transaction->isTesting());
    }

    public function testProcess()
    {
        $identity = $this->createMock(Identity::class);
        $encryption = $this->createMock(Encryption::class);
        $request = $this->createMock(Request::class);
        $endpoint = $this->createMock(Endpoint::class);
        $uri = $this->createMock(Uri::class);

        $request
            ->method('hasHeader')
            ->will($this->returnValueMap([
                ['X-PART', true],
                ['X-TS', true],
                ['X-LPL', true],
            ]))
        ;
        $request
            ->method('getHeaderLine')
            ->will($this->returnValueMap([
                ['X-PART', 'partnerkey'],
                ['X-TS', time()],
                ['X-LPL', 'signatureA'],
            ]))
        ;
        $identity
            ->method('sign')
            ->willReturn('signatureA')
        ;

        $request
            ->method('getUri')
            ->willReturn($uri)
        ;
        $uri
            ->method('getQuery')
            ->willReturn('crd=input')
        ;
        $encryption
            ->expects($this->once())
            ->method('decrypt')
            ->with('input')
            ->willReturn('data')
        ;
        $endpoint
            ->method('execute')
            ->with('data')
            ->willReturn('result')
        ;
        $encryption
            ->expects($this->once())
            ->method('encrypt')
            ->with('result')
            ->willReturn('blabla')
        ;

        $transaction = new Transaction($identity, $encryption, $request);
        $result = $transaction->process($endpoint);

        $this->assertEquals('blabla', $result);
    }

    public function testProcessFromPost()
    {
        $identity = $this->createMock(Identity::class);
        $encryption = $this->createMock(Encryption::class);
        $request = $this->createMock(Request::class);
        $endpoint = $this->createMock(Endpoint::class);

        $request
            ->method('hasHeader')
            ->will($this->returnValueMap([
                ['X-PART', true],
                ['X-TS', true],
                ['X-LPL', true],
            ]))
        ;
        $request
            ->method('getHeaderLine')
            ->will($this->returnValueMap([
                ['X-PART', 'lorem'],
                ['X-TS', time()],
                ['X-LPL', 'toto'],
            ]))
        ;
        $identity
            ->method('sign')
            ->willReturn('toto')
        ;

        $request
            ->method('getMethod')
            ->willReturn('POST')
        ;
        $request
            ->method('getBody')
            ->willReturn('input')
        ;
        $encryption
            ->expects($this->once())
            ->method('decrypt')
            ->with('input')
            ->willReturn('data')
        ;
        $endpoint
            ->method('execute')
            ->with('data')
            ->willReturn('result')
        ;
        $encryption
            ->expects($this->once())
            ->method('encrypt')
            ->with('result')
            ->willReturn('blabla')
        ;

        $transaction = new Transaction($identity, $encryption, $request);
        $result = $transaction->process($endpoint);

        $this->assertEquals('blabla', $result);
    }
}
