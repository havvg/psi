<?php

namespace Psi\Kernel;

use Psi\Exception\KernelNotBootedException;
use Psi\Plugin\PluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface KernelInterface
{
    /**
     * Bootstraps the current kernel to run the application.
     */
    public function boot();

    /**
     * Returns the list of plugins on this kernel.
     *
     * @return PluginInterface[]
     */
    public function getPlugins();

    /**
     * Returns the container built when booting the kernel.
     *
     * @return ContainerInterface
     *
     * @throws KernelNotBootedException
     */
    public function getContainer();

    /**
     * Returns the application root directory.
     *
     * @return string
     */
    public function getRootDir();

    /**
     * Returns the name of the application this kernel runs.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the version number of this kernel.
     *
     * @return string
     */
    public function getVersion();
}
