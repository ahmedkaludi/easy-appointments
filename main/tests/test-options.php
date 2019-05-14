<?php

/**
 * Class OptionsTest
 */
class OptionsTest extends WP_UnitTestCase
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

        $this->ea = new EasyAppointment();
        $this->ea->init_container();
        $this->container = $this->ea->get_container();
    }

    function tearDown()
    {
        parent::tearDown();

        $this->ea = null;
        $this->container = null;
    }

    /**
     * @test
     * Test if there are all options in db after install, value should be the same as number of default values
     */
    function test_di_options_from_db()
    {
        /** @var EAOptions $options */
        $options = $this->container['options'];

        $this->assertEquals(count($options->get_default_options()), count($options->get_options()));

    }

    /**
     * @test
     * Deep structure test if the default options are the same as one in db by compering json
     */
    function test_di_options_from_db_deep()
    {
        /** @var EAOptions $options */
        $options = $this->container['options'];

        $defaultOptions = $options->get_default_options();
        $currentOptions = $options->get_options();

        $this->assertEquals($defaultOptions, $currentOptions, 'Default options and real options have diff!');
    }

    function test_di_default_insert()
    {
        /** @var EAOptions $options */
        $options = $this->container['options'];

        $currentOptions = $options->get_default_options();

        $compare = array();

        foreach ($currentOptions as $key => $value) {
            $compare[] = array(
                'ea_key'   => $key,
                'ea_value' => $value,
                'type'     => 'default'
            );
        }

        $defaultInsert = $options->get_insert_options();

        $this->assertJsonStringEqualsJsonString(json_encode($defaultInsert), json_encode($compare));
    }

    function test_get_option_value_for_time_format()
    {
        $formats = array('00-24', 'am-pm');

        /** @var EAOptions $options */
        $options = $this->container['options'];

        $format = $options->get_option_value('time_format');

        $this->assertContains($format, $formats, 'Invalid time format option');
    }
}
