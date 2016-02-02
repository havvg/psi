<?php

namespace Psi\Plugin\Command;

use Psi\Application;
use Psi\Plugin\AbstractPlugin;

final class CommandPlugin extends AbstractPlugin
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
    public function __construct($service = 'psi.command.registry', $tag = 'console.command')
    {
        $this->service = $service;
        $this->tag = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $application)
    {
        $container = $application->getKernel()->getContainer();
        if ($container->has($this->service)) {
            $registry = $container->get($this->service);
            foreach ($registry as $command) {
                $application->add($command);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new CommandExtension($this->service);
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPasses()
    {
        return [
            new RegisterCommandsPass($this->service, $this->tag),
        ];
    }
}
