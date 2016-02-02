<?php

namespace Psi\Plugin\EventDispatcher;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $node = $builder->root('event_dispatcher');
        $node->canBeDisabled();

        return $builder;
    }
}
