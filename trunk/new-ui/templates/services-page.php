<?php
/**
 * Template: New Services UI - main page.
 *
 * Plain PHP/HTML markup only. All data loading/rendering happens client
 * side via assets/js/services.js against the existing ea_services /
 * ea_service / ea_delete_multiple_services AJAX endpoints,
 * mirroring the ea-naui Appointments page pattern.
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap ea-mnui-wrap">
    <div id="ea-mnui-services-app" class="ea-mnui">

        <header class="ea-mnui-header">
            <div class="ea-mnui-brand">
                <span class="ea-mnui-brand-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8 4V8M16 4V8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M3 12H21" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </span>
                <h1><?php esc_html_e('Services', 'easy-appointments'); ?></h1>
                <span class="ea-mnui-version">v<?php echo esc_html(EASY_APPOINTMENTS_VERSION); ?></span>
            </div>
            <div class="ea-mnui-header-actions">
                <a href="#" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-add-new">
                    <?php esc_html_e('Add service', 'easy-appointments'); ?>
                </a>
            </div>
        </header>

        <div class="ea-mnui-body">

            <!-- Toolbar -->
            <div class="ea-mnui-toolbar">
                <label class="ea-mnui-select-all-label">
                    <input type="checkbox" id="ea-mnui-select-all">
                    <?php esc_html_e('Select All', 'easy-appointments'); ?>
                </label>

                <div class="ea-mnui-toolbar-actions">
                    <a href="#" class="ea-mnui-btn ea-mnui-btn-danger ea-mnui-delete-selected" style="display:none;">
                        <?php esc_html_e('Delete Selected', 'easy-appointments'); ?>
                    </a>
                </div>

                <span id="ea-mnui-status-msg" class="ea-mnui-status-msg"></span>

                <div class="ea-mnui-search-wrap">
                    <input type="text" id="ea-mnui-search" class="ea-mnui-search"
                        placeholder="<?php esc_attr_e('Search by name, duration, price or color', 'easy-appointments'); ?>">
                </div>
            </div>

            <!-- Table -->
            <div class="ea-mnui-table-wrap">
                <table class="ea-mnui-table">
                    <thead>
                        <tr>
                            <th class="ea-mnui-col-check"></th>
                            <th><a href="#" class="ea-mnui-set-sort" data-key="id"><?php esc_html_e('Id', 'easy-appointments'); ?></a></th>
                            <th><a href="#" class="ea-mnui-set-sort" data-key="name"><?php esc_html_e('Name', 'easy-appointments'); ?></a></th>
                            <th><?php esc_html_e('Duration', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Slot Step', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Block Before', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Block After', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Daily Limit', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Price', 'easy-appointments'); ?></th>
                            <th><?php esc_html_e('Color', 'easy-appointments'); ?></th>
                            <th class="ea-mnui-col-actions"><?php esc_html_e('Actions', 'easy-appointments'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="ea-mnui-rows"></tbody>
                </table>
                <div id="ea-mnui-empty" class="ea-mnui-empty" style="display:none;">
                    <?php esc_html_e('No services found.', 'easy-appointments'); ?>
                </div>
            </div>

            <div id="ea-mnui-pagination" class="ea-mnui-pagination"></div>
        </div>
    </div>

    <!-- Add / Edit drawer -->
    <div id="ea-mnui-drawer-overlay" class="ea-mnui-drawer-overlay"></div>
    <div id="ea-mnui-drawer" class="ea-mnui-drawer ea-mnui-drawer-wide" tabindex="-1">
        <form id="ea-mnui-drawer-form">
            <div class="ea-mnui-drawer-header">
                <h2 id="ea-mnui-drawer-title"><?php esc_html_e('Add service', 'easy-appointments'); ?></h2>
                <button type="button" id="ea-mnui-drawer-close" class="ea-mnui-drawer-close" aria-label="<?php esc_attr_e('Close', 'easy-appointments'); ?>">&times;</button>
            </div>

            <div class="ea-mnui-drawer-body ea-mnui-services-form-grid">
                <div class="ea-mnui-field" data-field="name">
                    <label for="ea-mnui-input-name"><?php esc_html_e('Name', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <input type="text" id="ea-mnui-input-name" data-prop="name" required>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Name is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field" data-field="duration">
                    <label for="ea-mnui-input-duration"><?php esc_html_e('Duration (minutes)', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <input type="number" id="ea-mnui-input-duration" data-prop="duration" min="1" step="1" required>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Valid duration is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field" data-field="slot_step">
                    <label for="ea-mnui-input-slot_step">
                        <?php esc_html_e('Slot Step (minutes)', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span>
                        <span class="ea-mnui-tip" data-tooltip="<?php esc_attr_e('Interval in minutes between start times of available slots (e.g. 15, 30 or 60 min).', 'easy-appointments'); ?>">?</span>
                    </label>
                    <input type="number" id="ea-mnui-input-slot_step" data-prop="slot_step" min="1" step="1" required>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Valid slot step is required.', 'easy-appointments'); ?></div>
                </div>

                <div class="ea-mnui-field" data-field="block_before">
                    <label for="ea-mnui-input-block_before"><?php esc_html_e('Block Before (minutes)', 'easy-appointments'); ?></label>
                    <input type="number" id="ea-mnui-input-block_before" data-prop="block_before" min="0" step="1" value="0">
                </div>

                <div class="ea-mnui-field" data-field="block_after">
                    <label for="ea-mnui-input-block_after"><?php esc_html_e('Block After (minutes)', 'easy-appointments'); ?></label>
                    <input type="number" id="ea-mnui-input-block_after" data-prop="block_after" min="0" step="1" value="0">
                </div>

                <div class="ea-mnui-field" data-field="daily_limit">
                    <label for="ea-mnui-input-daily_limit"><?php esc_html_e('Daily Limit', 'easy-appointments'); ?></label>
                    <input type="number" id="ea-mnui-input-daily_limit" data-prop="daily_limit" min="0" step="1" value="0">
                    <p class="ea-mnui-field-hint"><?php esc_html_e('Set to 0 for unlimited bookings per day.', 'easy-appointments'); ?></p>
                </div>

                <div class="ea-mnui-field" data-field="advance_booking_days">
                    <label for="ea-mnui-input-advance_booking_days"><?php esc_html_e('Advance Booking Days', 'easy-appointments'); ?></label>
                    <input type="number" id="ea-mnui-input-advance_booking_days" data-prop="advance_booking_days" min="0" step="1" value="0">
                    <p class="ea-mnui-field-hint"><?php esc_html_e('Set how many days in advance bookings become available. Set to 0 to disable this check.', 'easy-appointments'); ?></p>
                </div>

                <div class="ea-mnui-field" data-field="price">
                    <label for="ea-mnui-input-price"><?php esc_html_e('Price', 'easy-appointments'); ?> <span class="ea-mnui-required">*</span></label>
                    <input type="number" id="ea-mnui-input-price" data-prop="price" min="0" step="0.01" required>
                    <div class="ea-mnui-field-error"><?php esc_html_e('Valid price is required.', 'easy-appointments'); ?></div>
                </div>

                <!-- Color picker -->
                <div class="ea-mnui-field" data-field="service_color">
                    <label><?php esc_html_e('Color', 'easy-appointments'); ?></label>
                    <div class="ea-mnui-color-picker-wrap">
                        <input type="hidden" id="ea-mnui-input-service_color" data-prop="service_color" value="#2563eb">
                        <div class="ea-mnui-color-preview" id="ea-mnui-color-preview" style="background-color:#2563eb;"></div>
                        <div class="ea-mnui-color-options" id="ea-mnui-color-options">
                            <?php
                            $colors = array(
                                '#FF6900', '#FCB900', '#7BDCB5', '#00D084',
                                '#8ED1FC', '#0693E3', '#ABB8C3', '#EB144C',
                                '#F78DA7', '#9900EF', '#fe4a49', '#2ab7ca',
                                '#fed766', '#e6e6ea', '#f4f4f8', '#4a4e4d',
                                '#095a62', '#3da4ab', '#f1dca3', '#fe8a71',
                                '#2563eb', '#dc2626', '#16a34a', '#ca8a04'
                            );
                            foreach ($colors as $color) {
                                echo '<span class="ea-mnui-color-option" data-color="' . esc_attr($color) . '" style="background-color:' . esc_attr($color) . ';"></span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="ea-mnui-field" data-field="description">
                    <label for="ea-mnui-input-description"><?php esc_html_e('Description', 'easy-appointments'); ?></label>
                    <?php
                    wp_editor(
                        '',
                        'ea-mnui-input-description',
                        array(
                            'textarea_name' => 'description',
                            'textarea_rows' => 5,
                            'media_buttons' => false,
                            'teeny'         => true,
                            'quicktags'     => false,
                            'tinymce'       => array(
                                'toolbar1' => 'bold,italic,bullist,numlist,link,unlink,undo,redo',
                                'toolbar2' => '',
                                'menubar'  => false,
                            ),
                        )
                    );
                    ?>
                </div>
            </div>

            <div class="ea-mnui-drawer-footer">
                <button type="button" class="ea-mnui-btn ea-mnui-btn-ghost ea-mnui-drawer-cancel"><?php esc_html_e('Cancel', 'easy-appointments'); ?></button>
                <button type="submit" class="ea-mnui-btn ea-mnui-btn-primary ea-mnui-drawer-save"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
            </div>
        </form>
    </div>

    <!-- Screen Loader -->
    <div id="ea-screen-loader"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
               background: rgba(255, 255, 255, 0.6); z-index: 999999;
               display: flex; align-items: center; justify-content: center;">
        <div class="ea-loader" style="display: flex; flex-direction: column; align-items: center;">
            <img src="<?php echo esc_url(plugins_url('src/assets/img/loader.svg', dirname(dirname(__FILE__)))); ?>"
                alt="Loading..." style="width: 60px; height: 60px; margin-bottom: 10px;">
            <div style="color: #333; font-size: 16px;"><?php esc_html_e('Loading Services...', 'easy-appointments'); ?></div>
        </div>
    </div>
</div>

