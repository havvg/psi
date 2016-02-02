<?php

namespace Psi\Test\Cases;

use Mockery\MockInterface;
use Psi\Application;
use Psi\Kernel\Kernel;
use Psi\Plugin\PluginInterface;
use Psi\Test\App\PsiTestKernel;
use Psi\Test\Fixtures\Command\ContainerAwareCommand;

/**
 * @covers \Psi\Application
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kernel|MockInterface
     */
    private $kernel;

    protected function setUp()
    {
        $this->kernel = \Mockery::spy(new PsiTestKernel());
        $this->kernel->shouldReceive('getPlugins')->andReturn([])->byDefault();
    }

    public function testKernelGetsBooted()
    {
        new Application($this->kernel);

        $this->kernel->shouldHaveReceived('boot')->once();
    }

    public function testPluginsAreBooted()
    {
        $this->kernel->shouldReceive('getPlugins')->andReturn([
            $plugin1 = \Mockery::spy(PluginInterface::class),
            $plugin2 = \Mockery::spy(PluginInterface::class),
        ]);

        new Application($this->kernel);

        $plugin1->shouldHaveReceived('boot')->once();
        $plugin2->shouldHaveReceived('boot')->once();
    }

    public function testInjectKernelContainerToCommand()
    {
        $container = \Mockery::mock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->kernel->shouldReceive('getContainer')->andReturn($container);

        $command = new ContainerAwareCommand();
        $application = new Application($this->kernel);
        $application->add($command);

        static::assertSame($container, $command->getContainer());
    }
}
