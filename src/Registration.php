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

use Mediapart\LaPresseLibre\Security\Encryption;

class Registration
{
    /**
     * @var string Route where your user will register into La Presse Libre platform.
     */
    const LINK_ROUTE = 'https://www.lapresselibre.fr/inscription-partenaire';

    /**
     * @var int
     */
    private $public_key;

    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * @param int        $public_key
     * @param Encryption $encryption
     */
    public function __construct($public_key, Encryption $encryption)
    {
        $this->public_key = $public_key;
        $this->encryption = $encryption;
    }

    /**
     * Generate the link that allows you former users to
     * register themselves into La Presse Libre platform.
     *
     * @param string $email
     * @param string $userName
     * @param string $guid
     * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Int%C3%A9gration-et-configuration-du-SDK#inscription-%C3%A0-la-presse-libre-depuis-une-plateforme-partenaire
     *
     * @return string
     */
    public function generateLink($email, $userName, $guid = null)
    {
        $user = $this->encryption->encrypt(
            [
                'Email' => $email,
                'Pseudo' => $userName,
                'Guid' => $guid,
            ],
            OPENSSL_RAW_DATA & OPENSSL_NO_PADDING
        );

        return sprintf(
            '%s?user=%s&partId=%s',
            self::LINK_ROUTE,
            rawurlencode($user),
            $this->public_key
        );
    }
}
