<?php

/**
 * This file is part of the Mediapart LaPresseLibre Library.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\LaPresseLibre;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;
use Psr\Http\Message\RequestInterface as Request;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\LaPresseLibre\Security\Encryption;

/**
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#g%C3%A9n%C3%A9ralit%C3%A9s
 */
class Transaction implements LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * Initiate a new transaction.
     *
     * @param int        $secret
     * @param Encryption $encryption
     * @param Request    $request
     *
     * To initiate a transaction, Request should have the following headers :
     *
     * - X-PART (int32): partner identifier code
     * - X-TS (timestamp): sending transaction datetime
     * - X-LPL (string): hashed signature
     *
     * @throws \InvalidArgumentException if one of these headers are missing
     * @throws \UnexpectedValueException if one of these headers has invalid value
     */
    public function __construct(Identity $identity, Encryption $encryption, Request $request)
    {
        $this->request = $request;
        $this->encryption = $encryption;
        $this->logger = new NullLogger();

        $signature = $identity->sign(
            $this->requireHeader('X-PART'),
            $this->requireHeader('X-TS')
        );

        if ($signature != $this->requireHeader('X-LPL')) {
            throw new \UnexpectedValueException(sprintf(
                'Request signed by %s but expected %s',
                $request->getHeaderLine('X-LPL'),
                $signature
            ));
        }
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    private function requireHeader($name)
    {
        if (!$this->request->hasHeader($name)) {
            throw new \InvalidArgumentException(sprintf(
                'Missing header %s',
                $name
            ));
        }

        return $this->request->getHeaderLine($name);
    }

    /**
     * Returns if the transaction is in testing mode.
     *
     * If true, the endpoint should returns valid response but without applying
     * its effetcs on database.
     * Based on the X-CTX HTTP request header.
     *
     * @return bool returns true if testing mode is active, false otherwise
     */
    public function isTesting()
    {
        return (bool) $this->request->hasHeader('X-CTX');
    }

    /**
     * Execute an endpoint and returns his result encrypted.
     *
     * @param Endpoint $endpoint
     *
     * @return string
     */
    public function process(Endpoint $endpoint)
    {
        if (in_array($this->request->getMethod(), ['PUT', 'POST'])) {
            $input = $this->request->getBody();
        } else {
            parse_str($this->request->getUri()->getQuery(), $query);
            $input = $query['crd'];
        }

        $data = $this->encryption->decrypt($input);
        $this->logger->debug('receive data', [$input, $data]);
        $result = $endpoint->execute($data, $this->isTesting());

        $noPaddingOption = OPENSSL_RAW_DATA & OPENSSL_NO_PADDING;
        $encryptedResult = $this->encryption->encrypt($result, $noPaddingOption);

        return $encryptedResult;
    }
}
