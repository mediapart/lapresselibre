<?php

/**
 * This file is part of the Mediapart LaPresseLibre Library.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\LaPresseLibre\Subscription;

final class Type
{
    const MONTHLY = 'mensuel';
    const ANNUAL = 'annuel';
    const OTHER = 'autre';

    /**
     * @return Array All subscription type.
     */
    public static function all()
    {
        return [
            self::MONTHLY,
            self::ANNUAL,
            self::OTHER,
        ];
    }
}
