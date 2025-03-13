<style>
    body {
        background-color: #f4f7fa;
        font-family: 'Arial', sans-serif;
    }

    .easy_container {
        max-width: 1000px;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 40px;
    }

    pre {
        background: #272c36;
        color: #f8f9fa;
        padding: 12px;
        border-radius: 5px;
        font-size: 20px !important;
        font-family: 'Courier New', monospace !important;
    }

    h1,
    h2,
    h3 {
        color: #2a3f5f;
    }

    .table th {
        background: #2a3f5f;
        color: white;
    }
</style>
<div class="easy_container">
    <h1 class="mb-4"><?php esc_html_e('Shortcode Documentation', 'easy-appointments'); ?></h1>
    <p><?php esc_html_e('To insert the front-end plugin on a page or post, use the following shortcodes.', 'easy-appointments'); ?></p>

    <h2><?php esc_html_e('Standard Form', 'easy-appointments'); ?></h2>
    <pre>[ea_standard]</pre>

    <h3><?php esc_html_e('Options:', 'easy-appointments'); ?></h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?php esc_html_e('Name', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('Description', 'easy-appointments'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>scroll_off<?php esc_html_e('Name', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Disable scroll {true, false}, default: "false"', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('default_date', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Set a default selected date (YYYY-MM-DD). Example: 2017-12-31', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('min_date', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Minimum selectable date. Example: 2018-12-31', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('max_date', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Maximum selectable date. Example: 2018-12-31', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('show_remaining_slots', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Display remaining slots {"0", "1"}, default: "0"', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td>show_week</td>
                <td><?php esc_html_e('Show week numbers in the calendar {"0", "1"}, default: "0"', 'easy-appointments'); ?></td>
            </tr>
        </tbody>
    </table>

    <h3><?php esc_html_e('Example:', 'easy-appointments'); ?></h3>
    <pre>[ea_standard scroll_off="true"]</pre>

    <h2><?php esc_html_e('Bootstrap Version – Responsive Layout', 'easy-appointments'); ?></h2>
    <pre>[ea_bootstrap]</pre>

    <h3><?php esc_html_e('Additional Options:', 'easy-appointments'); ?></h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?php esc_html_e('Name', 'easy-appointments'); ?></th>
                <th><?php esc_html_e('Description', 'easy-appointments'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php esc_html_e('width', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Set width (e.g., "800px"), default: "400px"', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('scroll_off', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Disable scroll {true, false}, default: "false"', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('layout_cols', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Column layout {1,2}, default: 1', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('location', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Predefined location (ID number), default: null', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('service', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Predefined service (ID number), default: null', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('worker', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Predefined worker (ID number), default: null', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('rtl', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Right-to-left label positioning {0,1}, default: 0', 'easy-appointments'); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e('block_days', 'easy-appointments'); ?></td>
                <td><?php esc_html_e('Block specific dates (YYYY-MM-DD format)', 'easy-appointments'); ?></td>
            </tr>
        </tbody>
    </table>

    <h3><?php esc_html_e('Example:', 'easy-appointments'); ?></h3>
    <pre>[ea_bootstrap width="800px" layout_cols="2"]</pre>

    <h2><?php esc_html_e('FullCalendar View', 'easy-appointments'); ?></h2>
    <p><strong><?php esc_html_e('Note:', 'easy-appointments'); ?></strong> <?php esc_html_e('This feature is under development, and documentation may change.', 'easy-appointments'); ?></p>
    <pre>[ea_full_calendar location="1" worker="1" service="1"]</pre>
</div>