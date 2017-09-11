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

use Mediapart\LaPresseLibre\Subscription\Status as SubscriptionStatus;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-cr%C3%A9ation-de-comptes
 */
class AccountCreation extends AccountChange
{
    /**
     * {@inheritdoc}
     */
    protected function resolveInput(array $arguments)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'Pseudo',
            'Mail',
            'Password',
            'TypeAbonnement',
            'DateSouscription',
            'DateExpiration',
            'Tarif',
            'CodeUtilisateur',
            'Statut',
        ]);

        $resolver->setAllowedTypes('Tarif', 'float');
        $resolver->setAllowedTypes('CodeUtilisateur', 'string');
        $resolver->setAllowedTypes('Statut', 'int');
        $resolver->setAllowedValues('Statut', SubscriptionStatus::all());

        return $resolver->resolve($arguments);
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveOutput(array $data)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'IsValid',
            'PartenaireID',
            'CodeUtilisateur',
            'CodeEtat',
        ]);

        $resolver->setAllowedTypes('IsValid', 'bool');
        $resolver->setAllowedTypes('PartenaireID', 'int');
        $resolver->setAllowedTypes('CodeUtilisateur', 'string');
        $resolver->setAllowedTypes('CodeEtat', 'int');
        $resolver->setAllowedValues('CodeEtat', self::allStates());

        return $resolver->resolve($data);
    }
}
