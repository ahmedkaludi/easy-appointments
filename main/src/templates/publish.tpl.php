<style>
    body {
        background-color: #f4f7fa;
        font-family: 'Arial', sans-serif;
    }

    .ezappoint-tabcontent {
        margin-right: 20px;
        margin-top: 20px;
        animation: fadeEffect 1s;
        border: 1px solid #c3c4c7;
    }

    .easy_container {
        background: #fff;
        padding: 25px;
    }

    pre {
        background: #0073aa;
        color: #f8f9fa;
        padding: 12px;
        border-radius: 5px;
        font-size: 20px !important;
        font-family: 'Courier New', monospace !important;
    }


    .table th {
        background: #0073aa;
        color: white;
    }
</style>
<div class="ezappoint_support_div ezappoint-tabcontent">
    <div class="easy_container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="mb-4"><?php esc_html_e('Shortcode Uses Guide', 'easy-appointments'); ?></h2>
            <div>
                <a target="_blank" href="https://easy-appointments.com/docs/installation-and-configuration-guide/#short-code"
                    style="padding: 8px 16px; background-color: #0073aa; color: white; text-decoration: none; border-radius: 4px;margin-right: 10px;">
                    <?php esc_html_e('Learn More', 'easy-appointments'); ?>
                </a>
                <a target="_blank" href="https://easy-appointments.com/contact-us/"
                    style="padding: 8px 16px; background-color: #0073aa; color: white; text-decoration: none; border-radius: 4px;">
                    <?php esc_html_e('Need Help?', 'easy-appointments'); ?>
                </a>
            </div>
        </div>
        <p><?php esc_html_e('To insert the front-end plugin on a page or post, use the following shortcodes.', 'easy-appointments'); ?></p>

        <h3><?php esc_html_e('Standard Form', 'easy-appointments'); ?></h3>
        <pre>[ea_standard]</pre>

        <h4><?php esc_html_e('Options:', 'easy-appointments'); ?></h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td><?php esc_html_e('Name', 'easy-appointments'); ?></td>
                    <td><?php esc_html_e('Description', 'easy-appointments'); ?></td>
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

        <h4><?php esc_html_e('Example:', 'easy-appointments'); ?></h4>
        <pre>[ea_standard scroll_off="true"]</pre>

        <h3><?php esc_html_e('Bootstrap Version – Responsive Layout', 'easy-appointments'); ?></h3>
        <pre>[ea_bootstrap]</pre>

        <h4><?php esc_html_e('Additional Options:', 'easy-appointments'); ?></h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td><?php esc_html_e('Name', 'easy-appointments'); ?></td>
                    <td><?php esc_html_e('Description', 'easy-appointments'); ?></td>
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

        <h4><?php esc_html_e('Example:', 'easy-appointments'); ?></h4>
        <pre>[ea_bootstrap width="800px" layout_cols="2"]</pre>

        <h3><?php esc_html_e('FullCalendar View', 'easy-appointments'); ?></h3>
        <p><strong><?php esc_html_e('Note:', 'easy-appointments'); ?></strong> <?php esc_html_e('This feature is under development, and documentation may change.', 'easy-appointments'); ?></p>
        <pre>[ea_full_calendar location="1" worker="1" service="1"]</pre>

        <h3><?php esc_html_e('Gutenberg Block Uses Guide', 'easy-appointments'); ?></h3>
        <pre><?php esc_html_e('Booking Appointments Block', 'easy-appointments'); ?></pre>
        <p><strong><?php esc_html_e('Note:', 'easy-appointments'); ?></strong> <?php esc_html_e('Embed a full appointment booking form directly into your page or post using this block. It allows visitors to book services based on selected location, service, and worker..', 'easy-appointments'); ?></p>
        <div style="">
            <h3 style="color: #333;"><?php esc_html_e('Booking Appointments Block', 'easy-appointments'); ?> How to Use:</h3>
            <ol style="color: #555; margin-top: 5px;">
            <li><?php esc_html_e('Go to', 'easy-appointments'); ?> <strong><?php esc_html_e('Pages', 'easy-appointments'); ?> &gt; </strong><?php esc_html_e('Add New (or edit an existing page)', 'easy-appointments'); ?> .</li>
                <li> <?php esc_html_e('Click the', 'easy-appointments'); ?> <strong>“+”</strong> <?php esc_html_e('to add a block and search for', 'easy-appointments'); ?> <strong><?php esc_html_e('"Booking Appointments"', 'easy-appointments'); ?></strong>.</li>
                <li><?php esc_html_e('Select the block labeled', 'easy-appointments'); ?> <strong><?php esc_html_e('Booking Appointments', 'easy-appointments'); ?></strong> <?php esc_html_e('with the EA logo', 'easy-appointments'); ?>.</li>
                <li><?php esc_html_e('Publish or update your page', 'easy-appointments'); ?>.</li>
            </ol>
        </div>
        <pre><?php esc_html_e('EA Full Calendar Block', 'easy-appointments'); ?></pre>
        <p><strong><?php esc_html_e('Note:', 'easy-appointments'); ?></strong> <?php esc_html_e('Shows a full calendar view of all scheduled appointments for a specific location, service, and worker.', 'easy-appointments'); ?></p>
        <div style="">
            <h3 style="color: #333;"><?php esc_html_e('Booking Appointments Block', 'easy-appointments'); ?> How to Use:</h3>
            <ol style="color: #555; margin-top: 5px;">
                <li><?php esc_html_e('Go to', 'easy-appointments'); ?> <strong><?php esc_html_e('Pages', 'easy-appointments'); ?> &gt; </strong><?php esc_html_e('Add New (or edit an existing page)', 'easy-appointments'); ?> .</li>
                <li> <?php esc_html_e('Click the', 'easy-appointments'); ?> <strong>“+”</strong> <?php esc_html_e('to add a block and search for', 'easy-appointments'); ?> <strong><?php esc_html_e('"Full View Calendar"', 'easy-appointments'); ?></strong>.</li>
                <li><?php esc_html_e('Select the block labeled', 'easy-appointments'); ?> <strong><?php esc_html_e('"EA Full Calendar"', 'easy-appointments'); ?></strong> <?php esc_html_e('with the EA logo', 'easy-appointments'); ?>.</li>
                <li><?php esc_html_e('Publish or update your page', 'easy-appointments'); ?>.</li>
            </ol>
        </div>
    </div>
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background-color: #f8fcff; max-width: 700px; text-align: center; margin: 20px auto;" id="ea-suggest-feature-box">
        <h2 style="font-size: 20px; margin-bottom: 10px;">
            👉 <span style="font-weight: bold;"><?php esc_html_e('Still not working as expected?', 'easy-appointments'); ?></span> 👈
        </h2>
        <p style="font-size: 16px; color: #333; margin-bottom: 20px;">
            <?php esc_html_e('We’re actively improving Easy Appointments. If something isn’t working or a feature is missing, let us know — your feedback helps us fix it faster!', 'easy-appointments'); ?>
        </p>
        <a href="https://easy-appointments.com/contact-us/" id="ea-suggest-feature-btn" style="display: inline-block; padding: 10px 20px; background-color: #1d70b8; color: white; text-decoration: none; border-radius: 4px; font-size: 16px;">
            <?php esc_html_e('Report or Suggest', 'easy-appointments'); ?>
        </a>
    </div>

</div>