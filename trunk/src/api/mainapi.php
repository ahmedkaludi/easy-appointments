<?php

class EasyEAMainApi
{
    /**
     * EasyEAMainApi constructor.
     * @param tad_EA52_Container $container
     */
    public function __construct($container)
    {
        $controller = new EasyEAApiFullCalendar($container['db_models'], $container['options'], $container['mail']);
        $controller->register_routes();

        $logController = new EasyEALogActions($container['db_models']);
        $logController->register_routes();

        $gdpr = new EasyEAGDPRActions($container['db_models']);
        $gdpr->register_routes();

        $vacation = new EasyEAVacationActions($container['db_models'], $container['options']);
        $vacation->register_routes();
    }

}