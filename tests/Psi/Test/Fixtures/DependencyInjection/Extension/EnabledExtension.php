<?php

namespace Psi\Test\Fixtures\DependencyInjection\Extension;

use Psi\DependencyInjection\Extension\EnabledExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class EnabledExtension extends Extension implements EnabledExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }
}
