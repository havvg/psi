<?php

namespace Psi\Kernel;

interface CacheAwareKernelInterface extends KernelInterface
{
    /**
     * Returns the directory to put cache content into.
     *
     * @return string
     */
    public function getCacheDir();
}
