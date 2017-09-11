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

abstract class Endpoint implements LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * Construct a new Endpoint to answer one of the API expected operation
     *
     * @param string   $operation Class name of the Endpoint
     * @param callable $callback
     *
     * @return Endpoint
     * @throws \LogicException if $operation is not a child of Endpoint
     */
    public static function answer($operation, $callback)
    {
        if (!in_array(self::class, class_parents($operation))) {
            throw new \LogicException(sprintf(
                '%s is not a child of %s',
                $operation,
                self::class
            ));
        }

        return new $operation($callback);
    }

    /**
     * @param callable $callback
     *
     * @throws \InvalidArgumentException if the callback is not callable.
     */
    private function __construct($callback)
    {
        $this->callback = $callback;
        $this->logger = new NullLogger();

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Create endpoint with invalid callback');
        }
    }

    /**
     * Execute the endpoint.
     *
     * @param array $data
     * @param bool  $isTesting
     *
     * @return mixed
     */
    public function execute($data, $isTesting = false)
    {
        $arguments = $this->resolveInput($data);
        $response = call_user_func($this->callback, $arguments, $isTesting);
        $result = $this->resolveOutput($response);

        $this->logger->debug(
            sprintf('Executed endpoint %s', get_class($this)),
            [$arguments, $result, $isTesting]
        );

        return $result;
    }

    /**
     * Verify and complete the expected input values
     * when calling the web service.
     *
     * @param array $arguments
     *
     * @return array Arguments.
     */
    abstract protected function resolveInput(array $arguments);

    /**
     * Verify and complete the expected output values
     * when calling the web service.
     *
     * @param array $arguments
     *
     * @return array Data
     */
    abstract protected function resolveOutput(array $data);
}
