<?php

/**
 * This file is part of the Mediapart LaPresseLibre Library.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\LaPresseLibre\Operation;

use Mediapart\LaPresseLibre\Endpoint;

abstract class AccountChange extends Endpoint
{
    /**
     * `CodeEtat` output argument could have the following values :
     *
     * @var int
     * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#param%C3%A8tres-de-sortie-1
     */
    const SUCCESS = 1;
    const FAILED_EXISTING_EMAIL = 2;
    const FAILED_EXISTING_USERNAME = 3;
    const FAILED = 4;

    public static function allStates()
    {
        return [
            self::SUCCESS,
            self::FAILED_EXISTING_EMAIL,
            self::FAILED_EXISTING_USERNAME,
            self::FAILED,
        ];
    }
}
