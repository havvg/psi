<?php

namespace Psi\Test\Cases\Plugin\Command;

use Psi\Plugin\Command\Registry;
use Psi\Test\Fixtures\Command\ContainerAwareCommand;

/**
 * @covers \Psi\Plugin\Command\Registry
 */
class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    private $registry;

    protected function setUp()
    {
        $this->registry = new Registry();
    }

    public function testIterator()
    {
        $command1 = new ContainerAwareCommand();
        $command2 = new ContainerAwareCommand();

        $this->registry->add($command1);
        $this->registry->add($command2);

        static::assertContains($command1, $this->registry);
        static::assertContains($command2, $this->registry);
    }

    public function testCountable()
    {
        static::assertCount(0, $this->registry);

        $this->registry->add(new ContainerAwareCommand());
        $this->registry->add(new ContainerAwareCommand());

        static::assertCount(2, $this->registry);
    }
}
