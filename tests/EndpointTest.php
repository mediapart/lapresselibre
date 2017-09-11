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

class EndpointTest extends TestCase
{
    public function testAnswerWithInvalidOperation()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(sprintf(
            '%s is not a child of %s',
            \stdClass::class,
            Endpoint::class
        ));

        $endpoint = Endpoint::answer(\stdClass::class, function () {
        });
    }

    public function testAnswerWithInvalidCallback()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Create endpoint with invalid callback');

        $endpoint = Endpoint::answer(Verification::class, 42);
    }
}
