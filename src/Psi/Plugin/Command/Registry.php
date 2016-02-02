<?php

namespace Psi\Plugin\Command;

use Symfony\Component\Console\Command\Command;

final class Registry implements \IteratorAggregate, \Countable
{
    /**
     * @var Command[]
     */
    private $commands = [];

    /**
     * Adds a command to this registry.
     *
     * @param Command $command
     *
     * @return $this
     */
    public function add(Command $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->commands);
    }
}
