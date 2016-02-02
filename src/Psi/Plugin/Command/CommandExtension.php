<?php

namespace Psi\Plugin\Command;

use Psi\DependencyInjection\Extension\EnabledExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class CommandExtension extends Extension implements EnabledExtensionInterface
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

        $definition = new Definition(Registry::class);
        $container->setDefinition($this->service, $definition);
    }
}
