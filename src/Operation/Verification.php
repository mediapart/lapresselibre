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
use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-v%C3%A9rification-de-comptes-existants
 */
class Verification extends Endpoint
{
    /**
     * {@inheritdoc}
     */
    protected function resolveInput(array $arguments)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'Mail' => null,
            'Password' => null,
        ]);

        $resolver->setRequired('CodeUtilisateur');
        $resolver->setAllowedTypes('Mail', ['string', 'null']);
        $resolver->setAllowedTypes('Password', ['string', 'null']);
        $resolver->setAllowedTypes('CodeUtilisateur', 'string');

        return $resolver->resolve($arguments);
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveOutput(array $data)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'TypeAbonnement' => null,
            'DateExpiration' => null,
            'DateSouscription' => null,
        ]);

        $resolver->setRequired(['Mail', 'CodeUtilisateur', 'AccountExist', 'PartenaireID']);
        $resolver->setAllowedTypes('Mail', 'string');
        $resolver->setAllowedTypes('CodeUtilisateur', 'string');
        $resolver->setAllowedValues('TypeAbonnement', array_merge(SubscriptionType::all(), [null]));
        $resolver->setAllowedTypes('DateExpiration', ['string', 'null']);
        $resolver->setAllowedTypes('DateSouscription', ['string', 'null']);
        $resolver->setAllowedTypes('AccountExist', ['boolean']);
        $resolver->setAllowedTypes('PartenaireID', 'int');

        return $resolver->resolve($data);
    }
}
