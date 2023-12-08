<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    </head>
    <body>
        <table border="0" cellpadding="15" cellspacing="0" width="500">
            <tbody>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php _e('Id', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php esc_html_e($data['id']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php _e('Status', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php esc_html_e($data['status']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php _e('Location', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php esc_html_e($data['location_name']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php _e('Service', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php esc_html_e($data['service_name']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php _e('Worker', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php esc_html_e($data['worker_name']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php _e('Date', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php esc_html_e($data['date']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php _e('Start', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php esc_html_e($data['start']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php _e('End', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php esc_html_e($data['end']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php _e('Created', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php esc_html_e($data['created']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php _e('Price', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php esc_html_e($data['price']);?></td>
            </tr>
            <tr>
                <td style="text-align: left; background-color: #CCFFFF;">IP</td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php esc_html_e($data['ip']);?></td>
            </tr>

            <?php
            $count = 1;
            foreach ($meta as $field) {
                if(array_key_exists($field->slug, $data)) {
                    if($count++ % 2 == 1) {
                        echo '<tr>
                                    <td style="text-align:left;">' . esc_html($field->label) . '</td>
                                    <td style="text-align: right; font-weight: bold;">' . esc_html($data[$field->slug]) . '</td>
                              </tr>';
                    } else {
                        echo '<tr>
                                    <td style="text-align:left; background-color: #CCFFFF;">' . esc_html($field->label) . '</td>
                                    <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;">' . esc_html($data[$field->slug]) . '</td>
                              </tr>';
                    }
                }
            }
            ?>
                </tbody>
        </table>
        <p style="font-weight: bold">- #link_confirm#</p>
        <p style="font-weight: bold">- #link_cancel#</p>
    </body>
</html>