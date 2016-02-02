<?php

namespace Psi;

use Psi\Kernel\KernelInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class Application extends BaseApplication
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->kernel->boot();

        parent::__construct($kernel->getName(), $kernel->getVersion());

        $this->boot();
    }

    /**
     * {@inheritdoc}
     */
    public function add(Command $command)
    {
        if ($command instanceof ContainerAwareInterface) {
            $command->setContainer($this->getKernel()->getContainer());
        }

        return parent::add($command);
    }

    /**
     * Returns the kernel this application is bound to.
     *
     * @return KernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Bootstraps the current application before running.
     */
    protected function boot()
    {
        foreach ($this->getKernel()->getPlugins() as $plugin) {
            $plugin->boot($this);
        }
    }
}
