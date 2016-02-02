<?php

namespace Psi\DependencyInjection\Compiler;

use Psi\DependencyInjection\Extension\EnabledExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class MergeEnabledExtensionsPass extends MergeExtensionConfigurationPass
{
    /**
     * Ensures enabled extensions being loaded by the container.
     *
     * @see EnabledExtensionInterface
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $extensions = array_filter($container->getExtensions(), function ($extension) {
            return $extension instanceof EnabledExtensionInterface;
        });

        $names = array_map(function (ExtensionInterface $extension) {
            return $extension->getAlias();
        }, $extensions);

        array_walk($names, function ($extension) use ($container) {
            // Only apply to extension, if there is no configuration supplied.
            if (!count($container->getExtensionConfig($extension))) {
                $container->loadFromExtension($extension, []);
            }
        });

        parent::process($container);
    }
}
