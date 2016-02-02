<?php

namespace Psi\Test\Cases;

use Psi\Application;
use Psi\Test\App\PsiTestKernel;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ApplicationFunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PsiTestKernel
     */
    private $kernel;

    /**
     * @var Application
     */
    private $application;

    protected function setUp()
    {
        $this->kernel = new PsiTestKernel('test', true);
        $this->application = new Application($this->kernel);
        $this->application->setAutoExit(false);
    }

    public function testApplicationRuns()
    {
        $output = $this->runApplication([]);

        static::assertContains('psi:demo  A demo command of the PSI framework.', $output->fetch());
    }

    public function testRunDemoCommand()
    {
        $output = $this->runApplication(['psi:demo']);

        static::assertContains('The demo command has been executed.', $output->fetch());
    }

    /**
     * Runs the application with the given input.
     *
     * @param array $input
     *
     * @return BufferedOutput
     */
    protected function runApplication(array $input)
    {
        $input = new ArrayInput($input);
        $output = new BufferedOutput();

        $this->application->run($input, $output);

        return $output;
    }
}
