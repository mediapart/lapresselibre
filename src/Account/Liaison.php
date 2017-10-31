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

use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Account\Account;
use Mediapart\LaPresseLibre\Account\Repository;

/**
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Liaison-de-compte-utilisateur-par-redirection
 */
class Liaison
{
    const RETURN_URL = 'https://beta.lapresselibre.fr/manage/link-result?lpl=%1$s&part=%2$s';
    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;
    const STATUS_CONFLICT = 3;

    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * @var Repository 
     */
    private $repository;

    /**
     * @var int
     */
    private $public_key;

    /**
     * @param Encryption $encryption
     * @param Repository $repository
     * @param int $public_key
     */
    public function __construct(Encryption $encryption, Repository $repository, $public_key)
    {
        $this->encryption = $encryption;
        $this->repository = $repository;
        $this->public_key = $public_key;
    }

    /**
     * Liaison de compte utilisateur par redirection
     *
     * @param string $lplUser
     * @param Account $logguedAccount
     * @return string
     */
    public function generateUrl($lplUser, Account $logguedAccount)
    {
        /* Le paramètre "lpluser" représente l'ID LPL de l'utilisateur qui 
           souhaite lier son compte. Il est chiffré en AES256 puis codé en 
           base64 en reprenant la méthode de chiffrement utilisée pour les 
           web services. */
        $code = $this->encryption->decrypt($lplUser);

        if ($existingAccount = $this->repository->find($code)) {

            /* En cas de conflit la valeur du statut que le partenaire doit
               retourner sera "3". Sauf évidement s'il s'agit du bon compte
               utilisateur. */
            $status = ($existingAccount != $logguedAccount) 
                ? self::STATUS_CONFLICT 
                : self::STATUS_SUCCESS
            ;

        } else {
            try {

                /* Si l'ID LPL reçu n'est pas déjà présent, le partenaire 
                   doit rechercher le compte utilisateur pour y rattacher 
                   L'ID LPL. Puis on retourne un statut "1" pour indiquer 
                   que la liaison s'est effectuée avec succès. */
                $account = new Account($logguedAccount->getEmail(), $code);
                $this->repository->save($account);
                $status = self::STATUS_SUCCESS;

            } catch (\Exception $e) {

                /* Le statut retourné par le partenaire LPL est "2" en cas 
                   d'erreur. */
                $status = self::STATUS_FAILURE;
            }
        }

        /* Le partenaire doit rediriger l'utilisateur vers l'url fournie par 
           LPL avec les paramètres : */
        return sprintf(
            self::RETURN_URL,

            /* "lpl" : composé de l'ID LPL et du statut. Ce paramètre sera 
               ensuite chiffré en AES puis codé en base64. 
               Exemple : { Guid: xxxx, statut: 1 } */
            rawurlencode(
                $this->encryption->encrypt(
                    [
                        'Guid' => $code,
                        'statut' => $status,
                    ],
                    OPENSSL_RAW_DATA & OPENSSL_NO_PADDING
                )
            ),

            /* "part" : qui représente le code du partenaire. */
            $this->public_key
        );
    }
}
