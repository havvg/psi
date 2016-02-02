<?php

namespace Psi\Test\Cases\Kernel;

use Psi\Exception\KernelNotBootedException;
use Psi\Kernel\CacheAwareKernelInterface;
use Psi\Kernel\Kernel;
use Psi\Test\App\PsiTestKernel;

/**
 * @covers \Psi\Kernel\Kernel
 */
class KernelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kernel
     */
    private $kernel;

    protected function setUp()
    {
        $this->kernel = new PsiTestKernel('test', true);
    }

    public function testKernelInformation()
    {
        static::assertInstanceOf(CacheAwareKernelInterface::class, $this->kernel);

        static::assertEquals('test', $this->kernel->getEnvironment());
        static::assertNotEmpty($this->kernel->getName());
        static::assertEquals(PsiTestKernel::VERSION, $this->kernel->getVersion());
    }

    public function testContainerRequiresBoot()
    {
        $this->expectException(KernelNotBootedException::class);

        $this->kernel->getContainer();
    }

    public function testContainerIsAvailableAfterBoot()
    {
        $this->kernel->boot();

        static::assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $this->kernel->getContainer());
    }
}
