<?php

/**
 * Class tad_EA52_ServiceProvider
 * @codeCoverageIgnore
 */
abstract class tad_EA52_ServiceProvider implements tad_EA52_ServiceProviderInterface
{
    /**
     * Whether the service provider will be a deferred one or not.
     *
     * @var bool
     */
    protected $deferred = false;

    /**
     * @var tad_EA52_Container
     */
    protected $container;


    /**
     * tad_EA52_ServiceProvider constructor.
     * @param tad_EA52_Container $container
     */
    public function __construct(tad_EA52_Container $container)
    {
        $this->container = $container;
    }

    /**
     * Whether the service provider will be a deferred one or not.
     *
     * @return bool
     */
    public function isDeferred()
    {
        return $this->deferred;
    }

    /**
     * Returns an array of the class or interfaces bound and provided by the service provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    /**
     * Binds and sets up implementations at boot time.
     */
    public function boot()
    {
        // no-op
    }
}
