<?php

class EASlotsLogic {
    private $WORKER = '0';
    private $MATCH_ALL = '1';
    private $LOCATION = '2';
    private $SERVICE = '3';
    private $EXCLUSIVE_WORKER = '4';

    private $PLACEHOLDER = '#DYNAMIC#';

    /**
     * @var EAOptions
     */
    protected $options;

    /**
     * @var wpdb
     */
    protected $wpdb;

    public function __construct($wpdb, $options)
    {
        $this->wpdb = $wpdb;
        $this->options = $options;
    }

    public function get_busy_slot_query($location, $service, $worker, $day, $app_id)
    {
        $mode = $this->options->get_option_value('multiple.work', '1');
        $table_name = "{$this->wpdb->prefix}ea_appointments";
        $static_part = "SELECT * FROM {$table_name} WHERE 
			{$this->PLACEHOLDER} AND 
			date <= %s AND
			end_date >= %s AND
			id <> %d AND 
			status NOT IN ('abandoned','canceled')";

        $params = array();

        switch ($mode) {
            case $this->LOCATION:
                $dynamic_part = 'location=%d';
                $params[] = $location;
                break;
            case $this->SERVICE:
                $dynamic_part = 'service=%d';
                $params[] = $service;
                break;
            case $this->MATCH_ALL:
                $dynamic_part = 'location=%d AND service=%d AND worker=%d';
                $params[] = $location;
                $params[] = $service;
                $params[] = $worker;
                break;
            case $this->WORKER:
            case $this->EXCLUSIVE_WORKER:
            default:
                $dynamic_part = 'worker=%d';
                $params[] = $worker;
                break;
        }


        $params[] = $day;
        $params[] = $day;
        $params[] = $app_id;
        $full_query = str_replace($this->PLACEHOLDER, $dynamic_part, $static_part);

        return $this->wpdb->prepare($full_query, $params);
    }

    public function is_exclusive_mode()
    {
        $mode = $this->options->get_option_value('multiple.work', '1');
        return $mode === $this->EXCLUSIVE_WORKER;
    }

    public function is_provider_is_busy($app, $location, $service)
    {
        return $app->service !== $service || $app->location !== $location;
    }
}