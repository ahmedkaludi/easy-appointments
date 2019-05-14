<?php

class EAMainApi
{
    /**
     * EAMainApi constructor.
     * @param tad_DI52_Container $container
     */
    public function __construct($container)
    {
        $controller = new EAApiFullCalendar($container['db_models']);
        $controller->register_routes();
    }

}