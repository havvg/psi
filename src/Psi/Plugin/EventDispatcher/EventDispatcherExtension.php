<?php

namespace Psi\Plugin\EventDispatcher;

use Psi\DependencyInjection\Extension\EnabledExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class EventDispatcherExtension extends Extension implements EnabledExtensionInterface
{
    /**
     * @var string
     */
    private $service;

    /**
     * Constructor.
     *
     * @param string $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$this->isConfigEnabled($container, $config)) {
            return;
        }

        $definition = new Definition('Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher');
        $definition->addArgument(new Reference('service_container'));

        $container->setDefinition($this->service, $definition);
    }
}
