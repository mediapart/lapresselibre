<?php

/**
 * This file is part of the Mediapart LaPresseLibre Library.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\LaPresseLibre\Security;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;

/**
 * Used to sign documents has described in the API specifications.
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#g%C3%A9n%C3%A9ralit%C3%A9s
 */
class Identity implements LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * @var string Name of the hashing algorithm
     */
    const HASH_ALGORITHM = 'SHA1';

    /**
     * @var string Pattern used to concatenate public/secret key and timestamp
     */
    const SIGNATURE_PATTERN = '%s+%s+%s';

    /**
     * @var string
     */
    private $secret_key;

    /**
     * @var \Datetime
     */
    protected $datetime;

    /**
     * @param string $secret_key
     */
    public function __construct($secret_key)
    {
        $this->secret_key = $secret_key;
        $this->datetime = new \DateTime();
        $this->logger = new NullLogger();
    }

    /**
     * Generate a signature based on your private key.
     *
     * @param string $public_key
     * @param int    $timestamp
     *
     * @return string
     */
    public function sign($public_key, $timestamp = null)
    {
        $timestamp = $timestamp ? $timestamp : $this->datetime->getTimestamp();
        $signchain = sprintf(self::SIGNATURE_PATTERN, $public_key, $timestamp, $this->secret_key);
        $signature = hash(self::HASH_ALGORITHM, $signchain);

        $this->logger->debug(
            'Generated signature',
            [$public_key, $timestamp, $signature]
        );

        return $signature;
    }

    /**
     * @return \Datetime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }
}
