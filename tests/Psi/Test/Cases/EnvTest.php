<?php

namespace Psi\Test\Cases;

use Psi\Env;

/**
 * @covers \Psi\Env
 */
class EnvTest extends \PHPUnit_Framework_TestCase
{
    const PREFIX = 'PSI_TEST__';

    public function parametersProvider()
    {
        return [
           [self::PREFIX.'FOO__BAR', 'foo bar', 'foo.bar'],
           [self::PREFIX.'FOO__BAR', 1, 'foo.bar'],
           [self::PREFIX.'FOO_BAR', true, 'foo_bar'],
        ];
    }

    /**
     * @dataProvider parametersProvider
     */
    public function testGetParameters($key, $value, $expected)
    {
        $_SERVER[$key] = $value;

        $parameters = Env::getParameters(self::PREFIX);

        static::assertSame($value, $parameters[$expected]);
    }
}
