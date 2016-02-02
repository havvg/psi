<?php

namespace Psi\Test\Cases\DependencyInjection\Compiler;

use Psi\DependencyInjection\Compiler\MergeEnabledExtensionsPass;
use Psi\Test\Fixtures\DependencyInjection\Extension\EnabledExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Psi\DependencyInjection\Compiler\MergeEnabledExtensionsPass
 */
class MergeEnabledExtensionsPassTest extends \PHPUnit_Framework_TestCase
{
    public function testEnabledExtensionIsLoaded()
    {
        $extension = new EnabledExtension();

        $builder = new ContainerBuilder();
        $builder->registerExtension($extension);

        $pass = new MergeEnabledExtensionsPass();
        $pass->process($builder);

        static::assertNotEmpty($builder->getExtensionConfig('enabled'));
    }
}
