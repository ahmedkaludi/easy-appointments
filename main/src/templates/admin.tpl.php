<script type="text/template" id="ea-settings-main">
<?php 
    get_current_screen()->render_screen_meta();
?>
    <div class="wrap">
        <div id="tab-content"></div>
    </div>
</script>

<!--Customize -->
<script type="text/template" id="ea-tpl-custumize">
    <div class="wp-filter">
        <div class="custom-tab-view">
            <!-- TAB SECTION -->
            <div class="tab-selection">
                <div class="tabs-list">
                    <a data-tab="tab-connections" class="selected" href="#">
                        <span class="icon icon-general"></span><span class="text-label"><?php esc_html_e('General', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-mail" href="#">
                        <span class="icon icon-mail"></span><span class="text-label"><?php esc_html_e('Mail Notifications', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-full-calendar" href="#">
                      <span class="icon icon-fullcalendar"></span><span class="text-label"><?php esc_html_e('FullCalendar Shortcode', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-user-access" href="#">
                        <span class="icon icon-workers"></span><span class="text-label"><?php esc_html_e('User access', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-labels" href="#">
                        <span class="icon icon-label"></span><span class="text-label"><?php esc_html_e('Labels', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-date-time" href="#">
                        <span class="icon icon-datetime"></span><span class="text-label"><?php esc_html_e('Date & Time', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-fields" href="#">
                        <span class="icon icon-fields"></span><span class="text-label"><?php esc_html_e('Custom Form Fields', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-captcha" href="#">
                        <span class="icon icon-recaptcha"></span><span class="text-label"><?php esc_html_e('Google reCAPTCHA v2', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-captcha-3" href="#">
                        <span class="icon icon-recaptcha"></span><span class="text-label"><?php esc_html_e('Google reCAPTCHA v3', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-form" href="#">
                        <span class="icon icon-redirect"></span><span class="text-label"><?php esc_html_e('Form Style & Redirect', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-gdpr" href="#">
                        <span class="icon icon-gdpr"></span><span class="text-label"><?php esc_html_e('GDPR', 'easy-appointments'); ?></span>
                    </a>
                    <a data-tab="tab-money" href="#">
                        <span class="icon icon-money"></span><span class="text-label"><?php esc_html_e('Money Format', 'easy-appointments'); ?></span>
                    </a>
                </div>
                <div class="button-wrap">
                    <button class="button button-primary btn-save-settings"><?php esc_html_e('Save', 'easy-appointments'); ?></button>
                </div>
            </div>

            <div id="tab-connections" class="form-section">
                <span class="separator vertical"></span>
                <div class="form-container" id="customize-general">
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Busy slots are calculated by same', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('IMPORTANT! This is used to calculate busy slots based on settings that are set here.', 'easy-appointments'); ?>"></span>
                        </div>
                        <select id="multiple-work" class="field" data-key="multiple.work" name="multiple.work">
                            <option value="0" data-tip="<?php esc_html_e('Use case example: Employee can only provide single service at the time.', 'easy-appointments'); ?>"><?php esc_html_e('Worker', 'easy-appointments'); ?></option>
                            <option value="4" data-tip="<?php esc_html_e('Use case example: Employee can only provide single service at the time and other services and locations are blocked during service one is provided.', 'easy-appointments'); ?>"><?php esc_html_e('Exclusive by Worker', 'easy-appointments'); ?></option>
                            <option value="2" data-tip="<?php esc_html_e('Use case example: Multiple employees share same location as resource.', 'easy-appointments'); ?>"><?php esc_html_e('Location', 'easy-appointments'); ?></option>
                            <option value="3" data-tip="<?php esc_html_e('Use case example: Service as a shared resource between employees.', 'easy-appointments'); ?>"><?php esc_html_e('Service', 'easy-appointments'); ?></option>
                            <option value="1" data-tip="<?php esc_html_e('Use case example: Worker can provide different service at different locations at the same time.', 'easy-appointments'); ?>"><?php esc_html_e('Worker, Location and Service', 'easy-appointments'); ?></option>
                        </select>
                        <small id="multiple-work-tip"></small>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Compatibility mode', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('If you can\'t EDIT or DELETE conecntion or any other settings, you should mark this option. NOTE: After saving this options you must refresh page!', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="compatibility.mode"
                                   name="compatibility.mode" type="checkbox" <% if
                            (_.findWhere(settings, {ea_key:'compatibility.mode'}).ea_value == "1") {
                            %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Max number of appointments', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Number of appointments that one visitor can make reservation before limit alert is shown. Appointments are counted during one day.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="max.appointments" name="max.appointments"
                               type="text"
                               value="<%- _.findWhere(settings, {ea_key:'max.appointments'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Max number of appointments for logged in user', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Number of appointments that one visitor can make reservation before limit alert is shown. Appointments are counted during one day.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="max.appointments_by_user" name="max.appointments_by_users"
                               type="text"
                               value="<%- _.findWhere(settings, {ea_key:'max.appointments_by_user'}).ea_value %>">
                               <small><?php esc_html_e('Keep 0 for no restriction', 'easy-appointments'); ?></small>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label><?php esc_html_e('Auto reservation', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Make reservation at moment user select date and time!', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="pre.reservation" name="pre.reservation"
                                   type="checkbox" <% if (_.findWhere(settings,
                            {ea_key:'pre.reservation'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Turn nonce off', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('if you have issues with validation code that is expired in form you can turn off nonce but you are doing that on your own risk.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="nonce.off" name="nonce.off"
                                   type="checkbox" <% if (_.findWhere(settings,
                            {ea_key:'nonce.off'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Default status', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Default status of Appointment made by visitor.', 'easy-appointments'); ?>"></span>
                        </div>
                        <select id="ea-select-status" class="field" name="ea-select-status" data-key="default.status">
                            <option value="pending"
                            <% if (_.findWhere(settings, {ea_key:'default.status'}).ea_value ==
                            "pending") {
                            %>selected="selected"<% } %>><%- eaData.Status.pending %></option>
                            <option value="confirmed"
                            <% if (_.findWhere(settings, {ea_key:'default.status'}).ea_value ==
                            "confirmed") {
                            %>selected="selected"<% } %>><%- eaData.Status.confirmed %></option>
                            <option value="reservation"
                            <% if (_.findWhere(settings, {ea_key:'default.status'}).ea_value ==
                            "reservation") {
                            %>selected="selected"<% } %>><%- eaData.Status.reservation %></option>
                        </select>
                        <div id="ea-select-status-notification" style="display: none"><?php esc_html_e('Reservation status is short term, if you don\'t change it within 5 minutes it will be set to cancelled' , 'easy-appointments');?></div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Compress shortcode output (removes new lines from templates).', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('WordPress can add auto paragraph html element for each line break. This option prevents WP from doing that on EA shortcode.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="shortcode.compress"
                                   name="shortcode.compress" type="checkbox" <% if
                            (_.findWhere(settings, {ea_key:'shortcode.compress'}).ea_value == "1") {
                            %>checked<% } %>>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-user-access" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item" style="background-color: #ccc">
                        <blockquote><?php esc_html_e('Note: Please use those options carefully because this will allow you to change which capability is needed to access EasyAppointments admin pages. Leave empty to use only default settings', 'easy-appointments'); ?></blockquote>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="user.access.locations"><?php esc_html_e('Locations Page', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Default capability: manage_options.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="user.access.locations"
                            name="user.access.locations" type="text"
                            value="<%- _.findWhere(settings, {ea_key:'user.access.locations'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="user.access.services"><?php esc_html_e('Services Page', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Default capability: manage_options.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="user.access.services"
                           name="user.access.services" type="text"
                           value="<%- _.findWhere(settings, {ea_key:'user.access.services'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="user.access.workers"><?php esc_html_e('Workers Page', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Default capability: manage_options.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="user.access.workers"
                           name="user.access.workers" type="text"
                           value="<%- _.findWhere(settings, {ea_key:'user.access.workers'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="user.access.connections"><?php esc_html_e('Connections Page', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Default capability: manage_options.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="user.access.connections"
                               name="user.access.connections" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'user.access.connections'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="user.access.reports"><?php esc_html_e('Reports Page', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Default capability: manage_options.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="user.access.reports"
                               name="user.access.reports" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'user.access.reports'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="form-wrap">
                            <?php _e('Current logged in user have:', 'easy-appointments'); ?> <small>x<?php
                            $data = get_userdata( get_current_user_id() );
                            if ( is_object( $data) ) {
                                echo implode(', ', array_keys($data->allcaps));
                            }
                            ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-mail" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Notifications', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('You can use this tags inside email content. Just place for example #id# inside mail template and that value will be replaced with value.', 'easy-appointments'); ?>"></span>
                        </div>
                        <table class='notifications form-table'>
                            <tbody>
                            <tr>
                                <td colspan="2">
                                    <p>
                                        <a class="mail-tab selected"
                                           data-textarea="#mail-pending"><?php esc_html_e('Pending', 'easy-appointments'); ?></a>
                                        <a class="mail-tab"
                                           data-textarea="#mail-reservation"><?php esc_html_e('Reservation', 'easy-appointments'); ?></a>
                                        <a class="mail-tab"
                                           data-textarea="#mail-canceled"><?php esc_html_e('Cancelled', 'easy-appointments'); ?></a>
                                        <a class="mail-tab"
                                           data-textarea="#mail-confirmed"><?php esc_html_e('Confirmed', 'easy-appointments'); ?></a>
                                        <a class="mail-tab"
                                           data-textarea="#mail-admin"><?php esc_html_e('Admin', 'easy-appointments'); ?></a>
                                    </p>
                                    <textarea id="mail-template" style="height: 150px;"
                                              name="mail-template"><%- _.findWhere(settings, {ea_key:'mail.pending'}).ea_value %></textarea>
                                </td>
                            </tr>
                            <tr style="display:none;">
                                <td>
                                    <textarea id="mail-pending" class="field"
                                              data-key="mail.pending"><%- _.findWhere(settings, {ea_key:'mail.pending'}).ea_value %></textarea>
                                </td>
                                <td>
                                    <textarea id="mail-reservation" class="field"
                                              data-key="mail.reservation"><%- _.findWhere(settings, {ea_key:'mail.reservation'}).ea_value %></textarea>
                                </td>
                            </tr>
                            <tr style="display:none;">
                                <td>
                                    <textarea id="mail-canceled" class="field"
                                              data-key="mail.canceled"><%- _.findWhere(settings, {ea_key:'mail.canceled'}).ea_value %></textarea>
                                </td>
                                <td>
                                    <textarea id="mail-confirmed" class="field"
                                              data-key="mail.confirmed"><%- _.findWhere(settings, {ea_key:'mail.confirmed'}).ea_value %></textarea>
                                </td>
                            </tr>
                            <tr style="display:none;">
                                <td colspan="2">
                                    <textarea id="mail-admin" class="field" data-key="mail.admin"><%- (_.findWhere(settings, {ea_key:'mail.admin'}) != null) ? _.findWhere(settings, {ea_key:'mail.admin'}).ea_value: '' %></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <a id="load-default-admin-template" href="#" style="padding-top: 5px; padding-bottom: 5px; display: none;"><?php esc_html_e('Load default admin template', 'easy-appointments'); ?></a>
                        <div><small><?php esc_html_e('Available tags', 'easy-appointments'); ?>: #id#, #date#, #start#, #end#, #status#, #created#, #price#, #ip#, #link_confirm#, #link_cancel#, #url_confirm#, #url_cancel#, #service_name#, #service_duration#, #service_price#, #worker_name#, #workeresc_html_email#, #worker_phone#, #location_name#, #location_address#, #location_location#, <?php echo implode(', ', EADBModels::get_custom_fields_tags()); ?></small></div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="mail.action.two_step"><?php esc_html_e('Two step action links in email', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Sometimes Mail servers can open links from email for inspection. That will trigger actions such as #link_confirm#, #link_cancel#. Mark this option if you want to have additional prompt for user action via links.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="mail.action.two_step" name="mail.action.two_step"
                                   type="checkbox" <% if (_.findWhere(settings,
                            {ea_key:'mail.action.two_step'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Pending notification emails', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Enter email adress that will receive new reservation notification. Separate multiple emails with , (comma)', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="pending.email" name="pending.email"
                               type="text"
                               value="<%- _.findWhere(settings, {ea_key:'pending.email'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Admin notification subject', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('You can use any tag that is available as in custom email notifications.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="pending.subject.email"
                               name="pending.subject.email" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'pending.subject.email'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Visitor notification subject', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('You can use any tag that is available as in custom email notifications.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="pending.subject.visitor.email"
                               name="pending.subject.visitor.email" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'pending.subject.visitor.email'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="send.worker.email"><?php esc_html_e('Send email to worker', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Mark this option if you want to employee receive admin email after filing the form.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="send.worker.email"
                                   name="send.worker.email" type="checkbox" <% if
                            (_.findWhere(settings, {ea_key:'send.worker.email'}).ea_value == "1") {
                            %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="send.user.email"><?php esc_html_e('Send email to user', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Mark this option if you want to user receive email after filing the form.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="send.user.email" name="send.user.email"
                                   type="checkbox" <% if (_.findWhere(settings,
                            {ea_key:'send.user.email'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Send from', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Send from email adress (Example: Name &lt;name@domain.com&gt;). Leave blank to use default address.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="send.from.email" name="send.from.email"
                               type="text"
                               value="<%- _.findWhere(settings, {ea_key:'send.from.email'}).ea_value %>">
                    </div>
                </div>
            </div>

            <div id="tab-full-calendar" class="form-section hidden">
              <span class="separator vertical"></span>
              <div class="form-container">
                  <div class="form-item">
                      <div class="label-with-tooltip">
                          <label for=""><?php esc_html_e('Allow public access to FullCalendar shortcode', 'easy-appointments'); ?></label>
                          <span class="tooltip tooltip-right"
                                data-tooltip="<?php esc_html_e('By default only logged in users can see data in FullCalendar. Mark this option if you want to allow public access for all.', 'easy-appointments'); ?>"></span>
                      </div>
                      <div class="field-wrap">
                          <input class="field" data-key="fullcalendar.public"
                                 name="fullcalendar.public" type="checkbox" <% if
                          (_.findWhere(settings, {ea_key:'fullcalendar.public'}).ea_value == "1") {
                          %>checked<% } %>>
                      </div>
                  </div>
                  <div class="form-item">
                      <div class="label-with-tooltip">
                          <label for=""><?php esc_html_e('Show event content in popup', 'easy-appointments'); ?></label>
                          <span class="tooltip tooltip-right"
                                data-tooltip="<?php esc_html_e('Popup dialog for event content.', 'easy-appointments'); ?>"></span>
                      </div>
                      <div class="field-wrap">
                          <input class="field" data-key="fullcalendar.event.show"
                                 name="fullcalendar.event.show" type="checkbox" <% if
                          (_.findWhere(settings, {ea_key:'fullcalendar.event.show'}).ea_value == "1") {
                          %>checked<% } %>>
                      </div>
                  </div>
                  <div class="form-item">
                      <div class="label-with-tooltip">
                          <label for=""><?php esc_html_e('Event content in popup', 'easy-appointments'); ?></label>
                          <span class="tooltip tooltip-right"
                                data-tooltip="<?php esc_html_e('Event content when clicked on event', 'easy-appointments'); ?>"></span>
                      </div>
                      <textarea id="fullcalendar-event-template" class="field" name="fullcalendar.event.template" data-key="fullcalendar.event.template"><%- (_.findWhere(settings, {ea_key:'fullcalendar.event.template'})).ea_value %></textarea>
                      <small><?php esc_html_e('Example', 'easy-appointments'); ?> : (<a href="https://easy-appointments.com/documentation/templates/" target="_blank"><?php esc_html_e('Full documentation', 'easy-appointments');?></a>)</small>
                      <div style="display: inline-block"><code>{= event.location_name}</code><small> / </small><code>{= language}</code><small> / </small><code>{= link_confirm}</code></div>
                      <small><?php esc_html_e('To get all available options use', 'easy-appointments'); ?> :</small>
                      <code>{= __CONTEXT__ | raw}</code>
                  </div>
              </div>
            </div>

            <div id="tab-labels" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                        <input class="field" data-key="trans.service" name="service" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'trans.service'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Location', 'easy-appointments'); ?></label>
                        <input class="field" data-key="trans.location" name="location" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'trans.location'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Worker', 'easy-appointments'); ?></label>
                        <input class="field" data-key="trans.worker" name="worker" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'trans.worker'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Done message', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Message that user receive after completing appointment', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="trans.done_message" name="done_message"
                               type="text"
                               value="<%- _.findWhere(settings, {ea_key:'trans.done_message'}).ea_value %>">
                    </div>
                </div>
            </div>

            <div id="tab-date-time" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Time format', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Notice : date/time formating for email notification are done by Settings > General.', 'easy-appointments'); ?>"></span>
                        </div>
                        <select data-key="time_format" class="field" name="time_format">
                            <option value="00-24"
                            <% if (_.findWhere(settings, {ea_key:'time_format'}).ea_value ===
                            "00-24") {
                            %>selected="selected"<% } %>>00-24</option>
                            <option value="am-pm"
                            <% if (_.findWhere(settings, {ea_key:'time_format'}).ea_value ===
                            "am-pm") {
                            %>selected="selected"<% } %>>AM-PM</option>
                        </select>
                    </div>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Calendar localization', 'easy-appointments'); ?></label>
                        <select data-key="datepicker" class="field" name="datepicker">
                            <% var langs = [
                            'af','ar','ar-DZ','az','be','bg','bs','ca','cs','cy-GB','da','de','el','en','en-AU','en-GB','en-NZ','en-US','eo','es','et','eu','fa','fi','fo','fr','fr-CA','fr-CH','gl','he','hi','hr','hu','hy','id','is','it','it-CH','ja','ka','kk','km','ko','ky','lb','lt','lv','mk','ml','ms','nb','nl','nl-BE','nn','no','pl','pt','pt-BR','rm','ro','ru','sk','sl','sq','sr','sr-SR','sv','ta','th','tj','tr','uk','vi','zh-CN','zh-HK','zh-TW'
                            ];
                            _.each(langs,function(item,key,list){
                            if(_.findWhere(settings, {ea_key:'datepicker'}).ea_value === item) { %>
                            <option value="<%- item %>" selected="selected"><%- item %></option>
                            <% } else { %>
                            <option value="<%- item %>"><%- item %></option>
                            <% }
                            });%>
                        </select>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Block time', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('(in minutes). Prevent visitor from making an appointment if there are less minutes than this.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="block.time" name="block.time" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'block.time'}).ea_value %>">
                    </div>
                </div>
            </div>

            <div id="tab-fields" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <span class="pure-text"><?php esc_html_e('Create all fields that you need. Custom order them by drag and drop.', 'easy-appointments'); ?></span>
                    </div>
                    <div class="form-item inline-fields">
                        <div class="form-item">
                            <label for="">Name</label>
                            <input type="text">
                        </div>
                        <div class="form-item">
                            <label for="">Type</label>
                            <select>
                                <option value="INPUT"><?php esc_html_e('Input', 'easy-appointments'); ?></option>
                                <option value="MASKED"><?php esc_html_e('Masked Input', 'easy-appointments'); ?></option>
                                <option value="SELECT"><?php esc_html_e('Select', 'easy-appointments'); ?></option>
                                <option value="TEXTAREA"><?php esc_html_e('Textarea', 'easy-appointments'); ?></option>
                                <option value="PHONE"><?php esc_html_e('Phone', 'easy-appointments'); ?></option>
                                <option value="EMAIL"><?php esc_html_e('Email', 'easy-appointments'); ?></option>
                            </select>
                        </div>
                        <button class="button button-primary btn-add-field button-field"><?php esc_html_e('Add', 'easy-appointments'); ?></button>
                    </div>
                    <div class="form-item">
                        <ul id="custom-fields"></ul>
                    </div>
                    <div class="form-item">
                        <span class="pure-text hint"><?php esc_html_e('* To use using the email notification for user there must be field named "email" or "e-mail" or field with type "email"', 'easy-appointments'); ?></span>
                    </div>
                </div>
            </div>

            <div id="tab-captcha" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Site key', 'easy-appointments'); ?></label>
                        <input style="width: 100%" class="field" data-key="captcha.site-key"
                               name="captcha.site-key" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'captcha.site-key'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <span class="pure-text hint"><?php esc_html_e('* Google reCAPTCHA key can be generated via', 'easy-appointments'); ?> <a
                                    href="https://www.google.com/recaptcha/admin" target="_blank">LINK</a></span>
                    </div>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Secret key', 'easy-appointments'); ?></label>
                        <input style="width: 100%" class="field" data-key="captcha.secret-key"
                               name="captcha.secret-key" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'captcha.secret-key'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <span class="pure-text hint"><?php esc_html_e('* If you want to use Captcha you must have auto reservation option turned off. If you don\'t want to use Captcha just leave fields empty.', 'easy-appointments'); ?></span>
                    </div>
                </div>
            </div>

            <div id="tab-captcha-3" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Site key', 'easy-appointments'); ?></label>
                        <input style="width: 100%" class="field" data-key="captcha3.site-key"
                               name="captcha3.site-key" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'captcha3.site-key'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <span class="pure-text hint"><?php esc_html_e('* Google reCAPTCHA key can be generated via', 'easy-appointments'); ?> <a
                                    href="https://www.google.com/recaptcha/admin" target="_blank">LINK</a></span>
                    </div>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Secret key', 'easy-appointments'); ?></label>
                        <input style="width: 100%" class="field" data-key="captcha3.secret-key"
                               name="captcha3.secret-key" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'captcha3.secret-key'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <span class="pure-text hint"><?php esc_html_e('* If you want to use Captcha you must have auto reservation option turned off. If you don\'t want to use Captcha just leave fields empty.', 'easy-appointments'); ?></span>
                    </div>
                    <div class="form-item">
                        <span class="pure-text hint"><?php esc_html_e('* Only request with recaptcha score 0.5 or greater will be processed. Others will be rejected as bot calls.', 'easy-appointments'); ?></span>
                    </div>
                </div>
            </div>

            <div id="tab-form" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Custom style', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Place here custom css styles. This will be included in both standard and bootstrap widget.', 'easy-appointments'); ?>"></span>
                        </div>
                        <textarea class="field" data-key="custom.css"><% if (typeof _.findWhere(settings, {ea_key:'custom.css'}) !== 'undefined') { %><%- (_.findWhere(settings, {ea_key:'custom.css'})).ea_value %><% } %></textarea>
                    </div>
                    <div class="form-item">
                        <label for="send.worker.email"><?php esc_html_e('Turn off css files', 'easy-appointments'); ?></label>
                        <div class="field-wrap">
                            <input class="field" data-key="css.off" name="css.off" type="checkbox"
                            <% if (_.findWhere(settings,
                            {ea_key:'css.off'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="form.label.above"><?php esc_html_e('Form label style', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Show labels above or inline with fields option on [ea_bootstrap] shortcode.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div>
                            <img data-value="0" class="form-label-option" title="inline" src="<?php echo plugin_dir_url( __DIR__ ) . '../img/label-inline.png';?>"/>
                            <img data-value="1" class="form-label-option" title="above" src="<?php echo plugin_dir_url( __DIR__ ) . '../img/label-above.png';?>"/>
                            <input class="field" type="hidden" name="form.label.above"
                                   data-key="form.label.above" value="<%- _.findWhere(settings,
                            {ea_key:'form.label.above'}).ea_value %>" />
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="label.from_to"><?php esc_html_e('Select label style', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Show From or From-To label on time slot in [ea_bootstrap] shortcode.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div>
                            <img data-value="1" class="select-label-option" title="From - To" width="200px" src="<?php echo plugin_dir_url( __DIR__ ) . '../img/label-from-to.png';?>"/>
                            <img data-value="0" class="select-label-option" title="From" width="200px" src="<?php echo plugin_dir_url( __DIR__ ) . '../img/label-from.png';?>"/>
                            <input class="field" type="hidden" name="label.from_to"
                                   data-key="label.from_to" value="<%- _.findWhere(settings,
                            {ea_key:'label.from_to'}).ea_value %>" />
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="send.worker.email"><?php esc_html_e('I agree field', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('I agree option at the end of form. If this is marked user must confirm "I agree" checkbox.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" type="checkbox" name="show.iagree"
                                   data-key="show.iagree"<% if (typeof _.findWhere(settings,
                            {ea_key:'show.iagree'}) !== 'undefined' && _.findWhere(settings,
                            {ea_key:'show.iagree'}).ea_value == '1') { %>checked<% } %> />
                        </div>
                    </div>
                    <hr />
                    <div class="form-item" style="background-color: #ccc">
                        <blockquote><?php esc_html_e('Note: you can use dynamic form values for redirect params. Redirect example: https://example.com/customer_name={{name}}. This will put value from custom form field with slug `name` to that redirect value. Please check custom form fields for slug names of the fields and just put them in {{}} where you need that param.', 'easy-appointments'); ?></blockquote>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Go to page', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('After a visitor creates an appointment on the front-end form. Leave blank to turn off redirect.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="submit.redirect" name="submit.redirect"
                               type="text"
                               value="<%- _.findWhere(settings, {ea_key:'submit.redirect'}).ea_value %>">
                    </div>
                    <div class="form-item subgroup">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Advance Go to', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Add custom redirect based on service.', 'easy-appointments'); ?>"></span>
                        </div>
                    </div>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                        <select id="redirect-service" class="field">
                            <% _.each(eaData.Services,function(item,key,list){ %>
                            <option value="<%- _.escape(item.id) %>"><%- _.escape(item.name) %></option>
                            <% });%>
                        </select>
                    </div>
                    <div class="form-item inline-fields">
                        <div class="form-item">
                            <label for=""><?php esc_html_e('Redirect to', 'easy-appointments'); ?></label>
                            <input id="redirect-url" name="redirect-url" type="text">
                        </div>
                        <button class="button button-primary btn-add-redirect button-field"><?php esc_html_e('Add advance redirect', 'easy-appointments'); ?></button>
                    </div>
                    <input type="hidden" id="advance-redirect" data-key="advance.redirect" class="field" name="advance.redirect" value="<%- _.escape(ea_settings['advance.redirect']) %>">
                    <div class="form-item">
                        <ul id="custom-redirect-list" class="list-form-item"></ul>
                    </div>
                    <hr>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('After cancel go to', 'easy-appointments'); ?></label>
                        <select data-key="cancel.scroll" class="field" name="cancel.scroll">
                            <% var langs = [
                            'calendar', 'worker', 'service', 'location'
                            ];
                            _.each(langs,function(item,key,list){
                            if(typeof _.findWhere(settings, {ea_key:'cancel.scroll'}) !==
                            'undefined' &&
                            _.findWhere(settings, {ea_key:'cancel.scroll'}).ea_value === item) { %>
                            <option value="<%- item %>" selected="selected"><%- item %></option>
                            <% } else { %>
                            <option value="<%- item %>"><%- item %></option>
                            <% }
                            });%>
                        </select>
                    </div>
                    <div class="form-item subgroup">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Advance Go to on Cancel', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Add custom cancels redirect based on service.', 'easy-appointments'); ?>"></span>
                        </div>
                    </div>
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Service', 'easy-appointments'); ?></label>
                        <select id="cancel-redirect-service" class="field">
                            <% _.each(eaData.Services,function(item,key,list){ %>
                            <option value="<%- _.escape(item.id) %>"><%- _.escape(item.name) %></option>
                            <% });%>
                        </select>
                    </div>
                    <div class="form-item inline-fields">
                        <div class="form-item">
                            <label for=""><?php esc_html_e('Redirect to', 'easy-appointments'); ?></label>
                            <input id="cancel-redirect-url" name="cancel-redirect-url" type="text">
                        </div>
                        <button class="button button-primary btn-add-cancel-redirect button-field"><?php esc_html_e('Add advance redirect', 'easy-appointments'); ?></button>
                    </div>
                    <div class="form-item">
                        <ul id="custom-cancel-redirect-list" class="list-form-item"></ul>
                    </div>
                    <input type="hidden" id="advance-cancel-redirect" data-key="advance_cancel.redirect" class="field" name="advance_cancel.redirect" value="<%- _.escape(ea_settings['advance_cancel.redirect']) %>">

                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="show.display_thankyou_note"><?php esc_html_e('Display Thank You Note', 'easy-appointments'); ?></label>
                        </div>
                        <div class="field-wrap">
                            <input class="field" type="checkbox" name="show.display_thankyou_note"
                                   data-key="show.display_thankyou_note"<% if (typeof _.findWhere(settings,
                            {ea_key:'show.display_thankyou_note'}) !== 'undefined' && _.findWhere(settings,
                            {ea_key:'show.display_thankyou_note'}).ea_value == '1') { %>checked<% } %> />
                        </div>
                    </div>
                    <div class="form-item" style="background-color: #ccc; padding: 15px;">
                        <blockquote style="margin: 0;">
                            <?php echo wp_kses_post(__('
                                <strong>Display Thank You Note:</strong> This screen is displayed immediately after a user successfully books an appointment using the booking form, Its featured Appointment summary and Action buttons.<br><br>
                                <strong>Action Buttons:</strong><br>
                                <ul style="margin: 0 0 0 20px; padding: 0;">
                                    <li><strong>Book New Appointment:</strong> Allows the user to return and book another appointment.</li>
                                    <li><strong>Add to Google Calendar:</strong> Its add the appointment directly to their Google Calendar for easy reminders.</li>
                                </ul>
                            ', 'easy-appointments')); ?>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div id="tab-gdpr" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="send.worker.email"><?php esc_html_e('Turn on checkbox', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('GDPR section checkbox.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" type="checkbox" name="gdpr.on" data-key="gdpr.on"<%
                            if (typeof _.findWhere(settings, {ea_key:'gdpr.on'}) !== 'undefined' &&
                            _.findWhere(settings, {ea_key:'gdpr.on'}).ea_value == '1') { %>checked<%
                            } %> />
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Label', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Label next to checkbox.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="gdpr.label" name="gdpr.label" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'gdpr.label'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Page with GDPR content', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Link to page with GDPR content.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="gdpr.link" name="gdpr.link" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'gdpr.link'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Error message', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Message if user don\'t mark the GDPR checkbox.', 'easy-appointments'); ?>"></span>
                        </div>
                        <input class="field" data-key="gdpr.message" name="gdpr.message" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'gdpr.message'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for=""><?php esc_html_e('Clear customer data older then 6 months', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('This action will remove custom form field values older then 6 months. After that appointments older then 6 months will not hold any customer related data.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap button">
                            <input class="field" type="checkbox" name="gdpr.auto_remove" style="margin-right: 10px;" data-key="gdpr.auto_remove"<%
                            if (typeof _.findWhere(settings, {ea_key:'gdpr.auto_remove'}) !== 'undefined' &&
                            _.findWhere(settings, {ea_key:'gdpr.auto_remove'}).ea_value == '1') { %>checked<%
                            } %> /> <?php esc_html_e('Auto remove data via Cron that runs once a day','easy-appointments');?><button class="button button-primary btn-gdpr-delete-data button-field" style="margin-left: 10px"><?php esc_html_e('Remove data now', 'easy-appointments'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-money" class="form-section hidden">
                <span class="separator vertical"></span>
                <div class="form-container">
                    <div class="form-item">
                        <label for=""><?php esc_html_e('Currency', 'easy-appointments'); ?></label>
                        <input class="field" data-key="trans.currency" name="currency" type="text"
                               value="<%- _.findWhere(settings, {ea_key:'trans.currency'}).ea_value %>">
                    </div>
                    <div class="form-item">
                        <label for="currency.before"><?php esc_html_e('Currency before price', 'easy-appointments'); ?></label>
                        <div class="field-wrap">
                            <input class="field" data-key="currency.before" name="currency.before"
                                   type="checkbox" <% if (_.findWhere(settings,
                            {ea_key:'currency.before'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="price.hide.service"><?php esc_html_e('Hide price in service select', 'easy-appointments'); ?></label>
                        <div class="field-wrap">
                            <input class="field" data-key="price.hide.service" name="price.hide.service"
                                   type="checkbox" <% if (_.findWhere(settings,
                            {ea_key:'price.hide.service'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="label-with-tooltip">
                            <label for="price.hide"><?php esc_html_e('Hide price', 'easy-appointments'); ?></label>
                            <span class="tooltip tooltip-right"
                                  data-tooltip="<?php esc_html_e('Hide price in whole customers form.', 'easy-appointments'); ?>"></span>
                        </div>
                        <div class="field-wrap">
                            <input class="field" data-key="price.hide" name="price.hide"
                                   type="checkbox" <% if (_.findWhere(settings,
                            {ea_key:'price.hide'}).ea_value == "1") { %>checked<% } %>>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
    </div>
</script>

<script type="text/template" id="ea-tpl-custom-forms">
    <li data-name="<%- _.escape(item.label) %>" style="display: list-item;">
        <div class="menu-item-bar">
            <div class="menu-item-handle">
                <span class="item-title"><span class="menu-item-title"><%- item.label %></span> <span
                            class="is-submenu" style="display: none;">sub item</span></span>
                <span class="item-controls">
                <span class="item-type"><%- item.type %></span>
                    <a class="single-field-options"><i class="fa fa-chevron-down"></i></a>
                </span>
            </div>
        </div>
    </li>
</script>

<script type="text/template" id="ea-tpl-custom-form-options">
<div class="field-settings">
    <% if (item.slug && item.slug.length > 0) { %>
    <p><label>Slug :</label>
        <input type="text" class="field-slug" name="field-slug"
               value="<%- item.slug %>">
    </p>
    <% } %>
    <p>
        <label>Label</label><input type="text" class="field-label" name="field-label" value="<%- item.label %>">
    </p>

    <% if (item.type !== "PHONE" && item.type !== "SELECT" && item.type !== "MASKED") { %>
    <p>
        <label>Placeholder</label><input type="text" class="field-mixed" name="field-mixed" value="<%- item.mixed %>">
    </p>
    <% } %>

    <% if (item.type !== "PHONE" && item.type !== "SELECT" && item.type !== "MASKED") { %>
    <p>
        <label>Default value</label><input type="text" class="field-default_value" name="field-default_value" value="<%- item.default_value %>">
        <small>You can put values from logged in user (list of keys: <?php echo EAUserFieldMapper::all_field_keys(); ?>)</small>
    </p>
    <% } %>

    <% if (item.type === "PHONE") { %>
    <p>
        <label>Default value</label><select class="field-default_value" name="field-default_value"><?php require __DIR__ . '/phone.list.tpl.php';?></select>
    </p>
    <% } %>

    <% if (item.type === "MASKED") { %>
    <p>
        <label>Mask</label><input type="text" class="field-default_value" name="field-default_value" value="<%- item.default_value %>">
        <p><?php esc_html_e('Mask options', 'easy-appointments');?> : </p>
        <code>9 : numeric</code> , <code>a : alphabetical</code> , <code>* : alphanumeric</code>
        <p><?php esc_html_e('Example', 'easy-appointments');?> : </p>
        <code>(99) 9999[9]-9999</code> , <code>999-999-9999</code> , <code>aa-9{1,4}</code>
    </p>
    <% } %>

    <% if (item.type === "SELECT") { %>
    <p>
        <label>Options :</label>
    </p>
    <p>
    <ul class="select-options">
        <% _.each(item.options, function(element) { %>
        <li data-element="<%- element %>"><%- element %><a href="#" class="remove-select-option"><i
                        class="fa fa-trash-o"></i></a></li>
        <% }); %>
    </ul>
    </p>
    <p><input type="text"><a href="#" class="add-select-option">&nbsp;&nbsp;<i class="fa fa-plus"></i> Add option</a>
    </p>
    <% } %>
    <p>
        <label>Required :</label><input type="checkbox" class="required" name="required" <% if (item.required == "1") {
        %>checked<% } %>>
    </p>
    <p>
        <label>Visible: </label>
        <select class="visible" name="visible">
            <option value="0"
            <% if (item.visible === "0") {
            %>selected="selected"<% } %>>No</option>
            <option value="1"
            <% if (item.visible === "1") {
            %>selected="selected"<% } %>>Yes</option>
            <option value="2"
            <% if (item.visible === "2") {
            %>selected="selected"<% } %>>No, but rendered as hidden field</option>
        </select>
    </p>
    <p><a href="#" class="deletion item-delete" data-id="<%=item.id%>">Delete</a> | <a href="#" class="item-save">Apply</a></p>
</div>
</script>

<script type="text/template" id="ea-tpl-advance-redirect">
    <div style="min-height: 380px; max-height: 380px;">

    </div>
    <div class="bulk-footer">
        <button id="close-advance-redirect" class="button-primary" disabled>Close</button>
    </div>
</script>

<script type="text/template" id="ea-tpl-single-advance-redirect">
    <li>
        <span class="bulk-value"><%- _.findWhere(locations, {id:row.location})?.name %></span>
        <span class="bulk-value"><%- _.findWhere(services,  {id:row.service})?.name %></span>
        <span class="bulk-value"><%- _.findWhere(workers,   {id:row.worker})?.name s%></span>
        <span style="display: inline-block;"><button class="button bulk-connection-remove">Remove</button></span>
    </li>
</script>
