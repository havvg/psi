<?php

namespace Psi\Test\Cases\Plugin;

use Mockery\MockInterface;
use Psi\DependencyInjection\Extension\EmptyExtension;
use Psi\Plugin\AbstractPlugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Psi\Plugin\AbstractPlugin
 */
class AbstractPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractPlugin|MockInterface
     */
    private $plugin;

    protected function setUp()
    {
        $this->plugin = \Mockery::mock(AbstractPlugin::class);
        $this->plugin->shouldAllowMockingProtectedMethods();
        $this->plugin->shouldDeferMissing();
    }

    public function testBuildEmptyPlugin()
    {
        $builder = \Mockery::spy(new ContainerBuilder());

        $this->plugin->build($builder);

        $builder->shouldNotHaveReceived('registerExtension');
        $builder->shouldNotHaveReceived('addCompilerPass');
    }

    public function testBuildWithExtension()
    {
        $extension = new EmptyExtension('example');
        $builder = \Mockery::spy(new ContainerBuilder());

        $this->plugin->shouldReceive('getContainerExtension')->andReturn($extension);
        $this->plugin->build($builder);

        $builder
            ->shouldHaveReceived('registerExtension')
            ->once()
            ->with($extension)
        ;
    }

    public function testBuildWithCompilerPass()
    {
        $pass = \Mockery::mock('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
        $builder = \Mockery::spy(new ContainerBuilder());

        $this->plugin->shouldReceive('getCompilerPasses')->andReturn([$pass]);
        $this->plugin->build($builder);

        $builder
            ->shouldHaveReceived('addCompilerPass')
            ->once()
            ->with($pass)
        ;
    }
}
