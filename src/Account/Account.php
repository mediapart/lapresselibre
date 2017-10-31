<?php

/**
 * This file is part of the Mediapart LaPresseLibre Library.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\LaPresseLibre\Account;

class Account
{
	/**
	 * @var string
	 */
	private $code;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @param string $email
	 * @param string $code
	 */
	public function __construct($email, $code = null)
	{
		$this->code = $code;
		$this->email = $email;
	}

	/**
	 * @return string|null
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}
}
