<?php

namespace Psi\Plugin\Command;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterCommandsPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $tag;

    /**
     * Constructor.
     *
     * @param string $service
     * @param string $tag
     */
    public function __construct($service, $tag)
    {
        $this->service = $service;
        $this->tag = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->service)) {
            return;
        }

        $definition = $container->getDefinition($this->service);

        $services = $container->findTaggedServiceIds($this->tag);
        foreach ($services as $id => $tags) {
            $definition->addMethodCall('add', [new Reference($id)]);
        }
    }
}
