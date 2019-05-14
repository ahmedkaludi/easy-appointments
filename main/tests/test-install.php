<?php

/**
 * Created by PhpStorm.
 * User: ameba
 * Date: 10/10/16
 * Time: 10:43 PM
 */
class EAInstallTest extends WP_UnitTestCase
{
    /**
     * @var tad_DI52_Container
     */
    protected $container;

    /**
     * @var EasyAppointment
     */
    protected $ea;

    function setUp()
    {
        parent::setUp();

        $uninstall = new EAUninstallTools();
        $uninstall->clear_database();

        $this->ea = new EasyAppointment();
        $this->ea->init_container();
        $this->container = $this->ea->get_container();

        /** @var EAInstallTools $install */
        $install = $this->container['install_tools'];
        $install->set_demo_data();
    }

    function tearDown()
    {
        parent::tearDown();

        $this->ea = null;
        $this->container = null;
    }

    function test_init_workers()
    {
        /** @var EADBModels $db_models */
        $db_models = $this->container['db_models'];
        $rows = $db_models->get_all_rows('ea_staff');

        $this->assertEquals(2, count($rows));
    }

    function test_init_locations()
    {
        /** @var EADBModels $db_models */
        $db_models = $this->container['db_models'];
        $rows = $db_models->get_all_rows('ea_locations');

        $this->assertEquals(2, count($rows));
    }

    function test_init_services_()
    {
        /** @var EADBModels $db_models */
        $db_models = $this->container['db_models'];
        $rows = $db_models->get_all_rows('ea_services');

        $this->assertEquals(2, count($rows));
    }
}