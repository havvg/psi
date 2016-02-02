<?php

namespace Psi\Test\App;

use Psi\Kernel\Kernel;
use Psi\Plugin\Command\CommandPlugin;
use Psi\Plugin\EventDispatcher\EventDispatcherPlugin;

class PsiTestKernel extends Kernel
{
    const VERSION = 'psi-test';

    /**
     * Constructor.
     *
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($environment = 'test', $debug = false)
    {
        parent::__construct($environment, $debug);
    }

    /**
     * {@inheritdoc}
     */
    public function getPlugins()
    {
        return [
            new CommandPlugin(),
            new EventDispatcherPlugin(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'..';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PSI Test Application';
    }
}
