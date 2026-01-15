<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    </head>
    <body>
        <table border="0" cellpadding="15" cellspacing="0" width="500" style="margin-bottom: 20px">
            <tbody>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php esc_html_e('Id', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php echo esc_html($data['id']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php esc_html_e('Status', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php echo esc_html($data['status']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php esc_html_e('Location', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php echo esc_html($data['location_name']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php esc_html_e('Service', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php echo esc_html($data['service_name']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php esc_html_e('Worker', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php esc_html($data['worker_name']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php esc_html_e('Date', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php echo esc_html($data['date']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php esc_html_e('Start', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php echo esc_html($data['start']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php esc_html_e('End', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php echo esc_html($data['end']);?></td>
            </tr>
            <tr>
                <td style="text-align:left; background-color: #CCFFFF;"><?php esc_html_e('Created', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php echo esc_html($data['created']);?></td>
            </tr>
            <tr>
                <td style="text-align:left;"><?php esc_html_e('Price', 'easy-appointments');?></td>
                <td style="text-align: right; font-weight: bold;"><?php echo esc_html($data['price']);?></td>
            </tr>
            <tr>
                <td style="text-align: left; background-color: #CCFFFF;">IP</td>
                <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;"><?php echo esc_html($data['ip']);?></td>
            </tr>

            <?php
            $easy_ea_count = 1;
            foreach ($meta as $easy_ea_field) {
                if(array_key_exists($easy_ea_field->slug, $data)) {
                    if($easy_ea_count++ % 2 == 1) {
                        echo '<tr>
                                    <td style="text-align:left;">' . esc_html($easy_ea_field->label) . '</td>
                                    <td style="text-align: right; font-weight: bold;">' . esc_html($data[$easy_ea_field->slug]) . '</td>
                              </tr>';
                    } else {
                        echo '<tr>
                                    <td style="text-align:left; background-color: #CCFFFF;">' . esc_html($easy_ea_field->label) . '</td>
                                    <td style="text-align: right; font-weight: bold; background-color: #CCFFFF;">' . esc_html($data[$easy_ea_field->slug]) . '</td>
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