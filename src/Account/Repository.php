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

interface Repository
{
	/**
	 * @param string $code
	 * @return Account|null
	 */
	public function find($code);

	/**
	 * @param Account $account
	 * @return void
	 */
	public function save(Account $account);
}
