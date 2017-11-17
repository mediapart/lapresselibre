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
 * Used to encrypt/decrypt messages has described in the API specifications.
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#g%C3%A9n%C3%A9ralit%C3%A9s
 */
class Encryption implements LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $iv;

    /**
     * @var int
     */
    private $options = 0;

    /**
     * @var string
     */
    private $method = 'AES-256-CBC';

    /**
     * @param mixed $password
     * @param mixed $iv
     * @param int   $options
     */
    public function __construct($password, $iv = null, $options = OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING)
    {
        if (null == $iv) {
            $iv_len = openssl_cipher_iv_length($this->method);
            $iv = openssl_random_pseudo_bytes($iv_len);
            var_dump($iv);
        }

        $this->password = $password;
        $this->iv = $iv;
        $this->options = $options;
        $this->logger = new NullLogger();
    }

    /**
     * @param string $message
     *
     * @return string Crypted message
     */
    public function encrypt($message, $options = null)
    {
        $options = !is_null($options) ? $options : $this->options;
        $result = json_encode($message);
        $result = openssl_encrypt(
            $result,
            $this->method,
            $this->password,
            $options,
            $this->iv
        );
        $result = base64_encode($result);

        $this->logger->debug('Encrypting message', [$message, $result]);

        return $result;
    }

    /**
     * @param string $message Crypted message
     *
     * @return string Uncrypted message
     */
    public function decrypt($message, $options = null)
    {
        $options = !is_null($options) ? $options : $this->options;
        $result = base64_decode($message);
        $result = openssl_decrypt(
            $result,
            $this->method,
            $this->password,
            $options,
            $this->iv
        );
        $result = rtrim($result, "\0");

        $decodedJson = json_decode($result, true);
        $result = null!==$decodedJson ? $decodedJson : $result;

        $this->logger->debug('Uncrypting message', [$message, $result]);

        return $result;
    }
}
