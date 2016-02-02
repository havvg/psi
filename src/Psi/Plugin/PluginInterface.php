<?php

namespace Psi\Plugin;

use Psi\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface PluginInterface
{
    /**
     * Builds the plugin by extending the container to its needs.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container);

    /**
     * Boots the plugin within the given application.
     *
     * @param Application $application
     */
    public function boot(Application $application);
}
