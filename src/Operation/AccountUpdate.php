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

use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;
use Mediapart\LaPresseLibre\Subscription\Status as SubscriptionStatus;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-mise-%C3%A0-jour-de-comptes
 */
class AccountUpdate extends AccountChange
{
    /**
     * {@inheritdoc}
     */
    protected function resolveInput(array $arguments)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'CodeUtilisateur',
            'TypeAbonnement',
            'DateSouscription',
            'DateExpiration',
            'Tarif',
            'Statut',
        ]);

        $resolver->setAllowedTypes('CodeUtilisateur', 'string');
        $resolver->setAllowedTypes('TypeAbonnement', 'string');
        $resolver->setAllowedTypes('Tarif', 'float');
        $resolver->setAllowedTypes('Statut', 'int');
        $resolver->setAllowedValues('TypeAbonnement', SubscriptionType::all());
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
