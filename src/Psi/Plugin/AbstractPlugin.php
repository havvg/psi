<?php

namespace Psi\Plugin;

use Psi\Application;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

abstract class AbstractPlugin implements PluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->registerExtension($this->getContainerExtension());

        foreach ($this->getCompilerPasses() as $pass) {
            $container->addCompilerPass($pass);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $application)
    {
    }

    /**
     * Returns the container extension of this plugin.
     *
     * @return ExtensionInterface|null
     */
    protected function getContainerExtension()
    {
        return [];
    }

    /**
     * Returns a list of compiler passes to be added to the container.
     *
     * @return CompilerPassInterface[]
     */
    protected function getCompilerPasses()
    {
        return [];
    }
}
