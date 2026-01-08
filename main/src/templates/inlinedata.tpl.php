<script>
    window.eaData = {};
    var ea = window.eaData;

    ea.Locations = <?php echo wp_json_encode(
        json_decode(
            $this->models->get_pre_cache_json(
                'ea_locations',
                $this->models->get_order_by_part( 'ea_locations' )
            ),
            true
        )
    ); ?>;

    ea.Services = <?php echo wp_json_encode(
        json_decode(
            $this->models->get_pre_cache_json(
                'ea_services',
                $this->models->get_order_by_part( 'ea_services' )
            ),
            true
        )
    ); ?>;

    ea.Workers = <?php echo wp_json_encode(
        json_decode(
            $this->models->get_pre_cache_json(
                'ea_staff',
                $this->models->get_order_by_part( 'ea_workers' )
            ),
            true
        )
    ); ?>;

    ea.MetaFields = <?php echo wp_json_encode(
        json_decode(
            $this->models->get_pre_cache_json(
                'ea_meta_fields',
                array( 'position' => 'ASC' )
            ),
            true
        )
    ); ?>;

    ea.Status = <?php echo wp_json_encode( $this->logic->getStatus() ); ?>;
</script>
