<script type="text/javascript">
    var ea_ajaxurl = '<?php echo esc_url( admin_url("admin-ajax.php") ); ?>';
</script>
<script type="text/template" id="ea-bootstrap-main">
    <div class="ea-bootstrap <%- settings.form_class %>" translate="no" style="max-width: <%- settings.width %>;">
        <form class="form-horizontal">
            <% if (settings.layout_cols === '2') { %>
            <div class="col-md-6" style="padding-top: 25px;">
                <% } %>
                <!-- LOCATION -->
                <div class="step form-group">
                    <div class="block"></div>
                    <label class="ea-label col-sm-4 control-label">
                        <?php echo esc_html($this->options->get_option_value('trans.location')); ?>
                    </label>
                    <div class="col-sm-8">
                        <select name="location" data-c="location" class="filter form-control">
                            <?php $this->get_options('locations', $location_id, $service_id, $worker_id, $code_params['select_placeholder']); ?>
                        </select>
                    </div>
                </div>
                <!-- WORKER -->
                <div class="step form-group">
                    <div class="block"></div>
                    <label class="ea-label col-sm-4 control-label">
                        <?php echo esc_html($this->options->get_option_value("trans.service")); ?>
                    </label>
                    <div class="col-sm-8">
                        <select name="service" data-c="service" class="filter form-control"
                                data-currency="<?php echo esc_attr($this->options->get_option_value("trans.currency")); ?>">
                            <?php $this->get_options('services', $location_id, $service_id, $worker_id, $code_params['select_placeholder']) ?>
                        </select>
                    </div>
                </div>
                <!-- SERVICE -->
                <div class="step form-group">
                    <div class="block"></div>
                    <label class="ea-label col-sm-4 control-label">
                        <?php echo esc_html($this->options->get_option_value("trans.worker")); ?>
                    </label>
                    <div class="col-sm-8">
                        <select name="worker" data-c="worker" class="filter form-control">
                            <?php $this->get_options('staff', $location_id, $service_id, $worker_id, $code_params['select_placeholder']) ?>
                        </select>
                    </div>
                </div>                
                <div class="step calendar" class="filter">
                    <div class="block"></div>
                    <div class="date"></div>
                </div>
                <div class="step" class="filter">
                    <div class="block"></div>
                    <div class="time"></div>
                </div>
                <% if (settings.layout_cols === '2') { %>
            </div>
            <div class="step final col-md-6">
                <% } else { %>
                <div class="step final">
                    <% } %>
                    <div class="ea_hide_show">
                    <div class="block"></div>
                    <h3><%- settings['trans.personal-informations'] %></h3>
                    <small><%- settings['trans.fields'] %></small>
                    <% if (settings['allow_customer_search'] == 1) { %>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo esc_html($this->options->get_option_value("trans.customer_search_label")); ?></label>
                                <div class="col-sm-8">
                                    <input id="ea_customer_search" class="form-control" type="text" placeholder="<?php echo esc_html($this->options->get_option_value("trans.customer_search_label")); ?>" />
                                </div>
                            </div>
                        
                    <% } %>
                    <% _.each(settings.MetaFields, function(item,key,list) { %>
                    <% if (item.visible == "0") { return; } %>
                    <% if (item.visible == "2") { %>
                    <input id="<%- item.slug %>" name="<%- item.slug %>" type="hidden" value="<%- item.default_value %>" class="custom-field" />
                    <% return; } %>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><%- item.label %> <% if (item.required == "1") { %>*<% }
                            %></label>
                        <div class="col-sm-8">
                            <!-- INPUT TYPE -->
                            <% if(item.type === 'INPUT') { %>
                            <input id="<%- item.slug %>"  class="form-control custom-field" maxlength="499" type="text" name="<%- item.slug %>" placeholder="<%- item.mixed %>" value="<%- item.default_value %>"
                            <% if (item.required == "1") { %>data-rule-required="true" data-msg-required="<%-
                            settings['trans.field-required'] %>"<% } %> <% if (item.validation == "email") {
                            %>data-rule-email="true" data-msg-email="<%- settings['trans.error-email'] %>"<% } %>>
                            <!-- INPUT MASKED -->
                            <% } else if(item.type === 'MASKED') { %>
                            <input id="<%- item.slug %>" class="form-control custom-field masked-field" <% if (item.required == "1") { %>data-rule-required="true" data-msg-required="<%- settings['trans.field-required'] %>"<% } %> type="text" name="<%- item.slug %>" placeholder="<%- item.mixed %>" data-inputmask="'mask':'<%- item.default_value %>'" />
                            <!-- PHONE TYPE -->
                            <% } else if(item.type === 'PHONE') { %>
                                <?php require __DIR__ . '/phone.field.tpl.php';?>
                            <!-- EMAIL TYPE -->
                            <% } else if(item.type === 'EMAIL') { %>
                            <input id="<%- item.slug %>" class="form-control custom-field" maxlength="499" type="text" name="<%- item.slug %>" placeholder="<%- item.mixed %>" value="<%- item.default_value %>"
                            <% if (item.required == "1") { %>data-rule-required="true" data-msg-required="<%- settings['trans.field-required'] %>"<% } %> data-rule-email="true" data-msg-email="<%- settings['trans.error-email'] %>">
                            <!-- SELECT TYPE -->
                            <% } else if(item.type === 'SELECT') { %>
                            <select id="<%- item.slug %>" class="form-control custom-field" name="<%- item.slug %>" <% if (item.required ==
                            "1") { %>aria-required="true" <% if (item.required == "1") { %>data-rule-required="true"<% }
                            %> data-msg-required="<%- settings['trans.field-required'] %>"<% } %>>
                            <% _.each(item.mixed.split(','),function(i,k,l) { %>
                            <% if (i == "-") { %>
                            <option value="">-</option>
                            <% } else { %>
                            <option value="<%- i %>"><%- i %></option>
                            <% }});%>
                            </select>
                            <!-- TEXTAREA TYPE -->
                            <% } else if(item.type === 'TEXTAREA') { %>
                            <textarea id="<%- item.slug %>" class="form-control custom-field" rows="3" maxlength="499" style="height: auto;" placeholder="<%- item.mixed %>"
                                      name="<%- item.slug %>" <% if (item.required == "1") { %>data-rule-required="true"
                            data-msg-required="<%- settings['trans.field-required'] %>"<% } %>></textarea>
                            <% } %>
                        </div>
                    </div>
                    <% });%>
                    </div>
                    <div class="form-group" style="display:none;">
                        <div class="block"></div>
                        <label class="ea-label col-sm-4 control-label">
                            <?php echo esc_html('Repeat Appointement','easy-appointments'); ?>
                        </label>
                        <div class="col-sm-8">
                            <select id="repeat_booking" data-c="repeat_booking" class="filter form-control">
                                <option value="0"><?php echo esc_html('Does Not Repeat','easy-appointments'); ?></option>
                                <option value="1"><?php echo esc_html('Repeat Weekly','easy-appointments'); ?></option>
                                <option value="2"><?php echo esc_html('Custom','easy-appointments'); ?></option>
                            </select>
                        </div>
                        <input type="hidden" name="repeat_booking" value="0" />
                        <input type="hidden" name="repeat_start_date" value="0" />
                        <input type="hidden" name="repeat_end_date" value="0" />
                        <div id="recurrence-summary" class="mt-3 text-muted" style="display: none; padding:20px;">
                            <strong><?php echo esc_html('Repeat every','easy-appointments'); ?>:</strong> <span id="summary-repeat-week"></span><br>
                            <strong><?php echo esc_html('Starts','easy-appointments'); ?>:</strong> <span id="summary-start-date"></span><br>
                            <strong><?php echo esc_html('Ends','easy-appointments'); ?>:</strong> <span id="summary-end-date"></span>
                        </div>
                    </div>
                    
                    <h3 id="booking-overview-header"><%- settings['trans.booking-overview'] %></h3>
                    <div id="booking-overview"></div>
                    <div class="ea_hide_show">
                    <% if (settings['show.iagree'] == '1') { %>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">&nbsp;</label>
                        <div class="col-sm-8">
                            <div class="checkbox">
                                <label>
                                    <input id="ea-iagree" name="iagree" type="checkbox" data-rule-required="true"
                                           data-msg-required="<%- settings['trans.field-iagree'] %>">
                                    <%- settings['trans.iagree'] %>
                                </label>
                            </div>
                        </div>
                    </div>
                    <% } %>
                    <% if (settings['gdpr.on'] == '1') { %>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">&nbsp;</label>
                        <div class="col-sm-8">
                            <div class="checkbox">
                                <label class="gdpr">
                                    <input id="ea-gdpr" name="gdpr" type="checkbox" data-rule-required="true"
                                           data-msg-required="<%- settings['gdpr.message'] %>">
                                    <% if (settings['gdpr.link'] != '') { %>
                                        <a href="<%- settings['gdpr.link'] %>" target="_blank"><%- settings['gdpr.label'] %></a>
                                    <% } else {%>
                                        <%- settings['gdpr.label'] %>
                                    <% } %>
                                </label>
                            </div>
                        </div>
                    </div>
                    <% } %>

                    <% if (settings['captcha.site-key'] !== '') { %>
                        <div style="width: 100%; padding: 20px;" class="g-recaptcha" data-sitekey="<%- settings['captcha.site-key'] %>"></div>
                    <% } %>

                    <?php 
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo apply_filters('ea_payment_select', ''); ?>
                    <?php 
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo apply_filters('ea_stripe_checkout', ''); ?>
                    <?php 
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo apply_filters('ea_razorpay_checkout', ''); ?>

                    <div class="form-group">
                        <div class="col-sm-12 ea-actions-group" style="display: inline-flex; align-items: center; justify-content: center;">
                            <?php 
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo apply_filters('easy_ea_checkout_button', '<button class="ea-btn ea-submit btn btn-primary booking-button"><%- settings[\'trans.submit\'] %></button>'); ?>
                            <button class="ea-btn ea-cancel btn btn-default"><%- settings['trans.cancel'] %></button>
                        </div>
                    </div>
                </div>
                <% if (settings.layout_cols === '2') { %>
            </div>
            <% } %>
                </div>
        </form>
    </div>
<div id="ea-loader"></div>

<div id="custom-recurrence-modal" style="display:none; position:fixed; top:20%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; border-radius:8px; z-index:10000; width:420px;">
  <h4>Custom recurrence</h4>
    <div class="row">
        <label class="col-sm-4"><?php echo esc_html('Repeat every','easy-appointments'); ?></label>
        <input type="number" min="1" id="modal-repeat-week" value="2" class="col-sm-4"> <?php echo esc_html('weeks','easy-appointments'); ?>
    </div>

    <div class="mt-3 row" style="margin-top: 10px;">
        <label class="col-sm-4"><?php echo esc_html('Starts','easy-appointments'); ?></label>
        <input class="col-sm-4" type="date" id="modal-start-date" />
    </div>

    <div class="mt-3">
        <label><?php echo esc_html('Ends','easy-appointments'); ?></label><br>
        <input type="radio" name="modal-end-type" value="never" id="modal-end-never" checked> 
        <label for="modal-end-never"><?php echo esc_html('Never','easy-appointments'); ?></label><br>
        <input type="radio" name="modal-end-type" value="date" id="modal-end-on"> 
        <label for="modal-end-on"><?php echo esc_html('On','easy-appointments'); ?></label>
        <input type="date" id="modal-end-date" disabled>
    </div>

  <div class="mt-3" style="margin-top: 10px;">
    <button type="button" id="modal-save-btn" class="btn btn-primary"><?php echo esc_html('Done','easy-appointments'); ?></button>
    <button type="button" id="modal-cancel-btn" class="btn btn-default"><?php echo esc_html('Cancel','easy-appointments'); ?></button>
  </div>
</div>

<!-- Optional: Light overlay -->
<div id="custom-recurrence-overlay" style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:9999;"></div>





</script>