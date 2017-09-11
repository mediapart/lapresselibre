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

/**
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#statut-de-labonnement
 */
final class Status
{
    const ACTIVE = 1;
    const WAITING_FOR_VALIDATION = 2;
    const SUSPENDED = 3;
    const TERMINATED = 4;
    const CANCELED = 5;
    const IN_PAYMENT = 6;
    const REFUNDED = 7;

    /**
     * @return Array All subscription statuses.
     */
    public static function all()
    {
        return [
            self::ACTIVE,
            self::WAITING_FOR_VALIDATION,
            self::SUSPENDED,
            self::TERMINATED,
            self::CANCELED,
            self::IN_PAYMENT,
            self::REFUNDED,
        ];
    }
}
