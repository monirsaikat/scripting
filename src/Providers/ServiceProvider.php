<?php

namespace Src\Providers;

abstract class ServiceProvider
{
    /**
     * Register services within the container.
     *
     * @param \Pimple\Container $container
     */
    abstract public function register(\Pimple\Container $container);
}
