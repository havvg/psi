<?php

namespace Psi\Plugin\EventDispatcher;

use Psi\Application;
use Psi\Plugin\AbstractPlugin;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

final class EventDispatcherPlugin extends AbstractPlugin
{
    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $listenerTag;

    /**
     * @var string
     */
    protected $subscriberTag;

    /**
     * Constructor.
     *
     * @param string $service
     * @param string $listenerTag
     * @param string $subscriberTag
     */
    public function __construct($service = 'event_dispatcher', $listenerTag = 'kernel.event_listener', $subscriberTag = 'kernel.event_subscriber')
    {
        $this->service = $service;
        $this->listenerTag = $listenerTag;
        $this->subscriberTag = $subscriberTag;
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $application)
    {
        $container = $application->getKernel()->getContainer();
        if ($container->has($this->service)) {
            $application->setDispatcher($container->get($this->service));
        }
    }

    /**
     * Returns the container extension of this plugin.
     *
     * @return ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new EventDispatcherExtension($this->service);
    }

    /**
     * Returns a list of compiler passes to be added to the container.
     *
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses()
    {
        return [
            new RegisterListenersPass($this->service, $this->listenerTag, $this->subscriberTag),
        ];
    }
}
