;(function ( $, window, document, undefined ) {

    var pluginName = "eaStandard",

        defaults = {
            overview_selector: "#ea-appointments-overview",
            overview_template: null,
            initScrollOff: false
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.$element = jQuery(element);
        this.settings = jQuery.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    jQuery.extend(Plugin.prototype, {
        vacation: function(workerId, day, serviceId) {
            serviceId = serviceId || null;
            var response = [true, day, ''];

            if (typeof ea_service_start_data !== 'undefined') {
                jQuery.each(ea_service_start_data, function(index, service_start_data) {
                    if (serviceId == service_start_data.id && jQuery.inArray(day, service_start_data.booking_date_skip) !== -1) {
                        response = [false, 'blocked vacation', 'Not Available'];
                    }
                });
            }

            // block days from shortcode
            if (Array.isArray(ea_settings.block_days) && ea_settings.block_days.includes(day)) {
                return [
                    false,
                    'blocked',
                    ea_settings.block_days_tooltip
                ];
            }

            if (!Array.isArray(ea_vacations) || ea_vacations.length === 0) {
                return response;
            }

            jQuery.each(ea_vacations, function(index, vacation) {
                // Check events
                // Case we have workers selected
                if (vacation.workers && vacation.workers.length > 0) {
                    // extract worker ids
                    var workerIds = jQuery.map(vacation.workers, function(worker) {
                        return worker.id;
                    });
                    // selected worker is not in vacation list exit
                    if (jQuery.inArray(workerId, workerIds) === -1) {
                        return true;
                    }

                }

                if (jQuery.inArray(day, vacation.days) === -1) {
                    return true;
                }

                // Check if it's a partial vacation
                var isPartial = false;
                var rawStart = null;
                var rawEnd = null;

                if (vacation.time) {
                    if (typeof vacation.time === 'object') {
                        if (vacation.time.fullDay === false || vacation.time.fullDay === '0' || vacation.time.fullDay === 0) {
                            isPartial = true;
                            rawStart = vacation.time.startTime || vacation.time.start || vacation.time.from || vacation.time.time_from;
                            rawEnd = vacation.time.endTime || vacation.time.end || vacation.time.to || vacation.time.time_to;
                        }
                    }
                } else if (vacation.fullDay === false || vacation.fullDay === 0 || vacation.fullDay === '0') {
                    isPartial = true;
                    rawStart = vacation.startTime || vacation.start || vacation.from || vacation.time_from;
                    rawEnd = vacation.endTime || vacation.end || vacation.to || vacation.time_to;
                } else if (vacation.time_from && vacation.time_to) {
                    isPartial = true;
                    rawStart = vacation.time_from;
                    rawEnd = vacation.time_to;
                }

                if (isPartial && rawStart && rawEnd) {
                    var startTime = moment(rawStart, ['HH:mm', 'H:mm', 'HH:mm:ss']);
                    if (!startTime.isValid()) {
                        startTime = moment(rawStart);
                    }
                    var endTime = moment(rawEnd, ['HH:mm', 'H:mm', 'HH:mm:ss']);
                    if (!endTime.isValid()) {
                        endTime = moment(rawEnd);
                    }

                    if (startTime.isValid() && endTime.isValid()) {
                        if (!window.ea_partial_vacations) window.ea_partial_vacations = [];
                        window.ea_partial_vacations = window.ea_partial_vacations.filter(function(v) {
                            return !(v.day === day && v.workerId == workerId);
                        });
                        window.ea_partial_vacations.push({
                            day: day,
                            start: startTime.format('HH:mm'),
                            end: endTime.format('HH:mm'),
                            workerId: workerId,
                            tooltip: vacation.tooltip || ''
                        });
                        return true; // don't block the whole day
                    }
                }

                response = [false, 'blocked vacation', vacation.tooltip];

                return false;
            });

            return response;
        },
        /**
         * Plugin init
         */
        init: function () {
            var plugin = this;

            // Sanitize template HTML: backticks break _.template()'s new Function()
            function safeTemplate(selector) {
                var html = jQuery(selector).html();
                if (!html) {
                    return function(data) {
                        var dynamicHtml = jQuery(selector).html();
                        if (dynamicHtml) {
                            dynamicHtml = dynamicHtml.replace(/`/g, '&#96;');
                            try {
                                return _.template(dynamicHtml)(data);
                            } catch (e) {
                                console.error('EA: Template compilation error for ' + selector + ':', e.message);
                            }
                        }
                        return '<div></div>';
                    };
                }
                html = html.replace(/`/g, '&#96;');
                try {
                    return _.template(html);
                } catch (e) {
                    console.error('EA: Template compilation error for ' + selector + ':', e.message);
                    return _.template('<div></div>');
                }
            }

            this.settings.overview_template = safeTemplate(this.settings.overview_selector);

            // close plugin if something is missing
            if (!this.settingsOk()) {
                return;
            }

            this.$element.find('.ea-phone-number-part, .ea-phone-country-code-part').change(function() {
                plugin.parsePhoneField($(this));
            });

            // handle form validation with scroll to field with error
            this.$element.find('form').validate({
                focusInvalid: false,
                invalidHandler: function(form, validator) {
                    if (!validator.numberOfInvalids())
                        return;
                    $('html, body').animate({
                        scrollTop: ($(validator.errorList[0].element).offset().top - 30)
                    }, 1000);
                }
            });

            // select change event
            this.$element.find('select').change(jQuery.proxy( this.getNextOptions, this ));

            jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ea_settings.datepicker] );

            var firstDay = ea_settings.start_of_week;
            var minDate = (ea_settings.min_date === null) ? 0 : ea_settings.min_date;

            // datePicker
            this.$element.find('.date').datepicker({
                onSelect: jQuery.proxy(plugin.dateChange, plugin),
                dateFormat: 'yy-mm-dd',
                minDate: minDate,
                maxDate: ea_settings.max_date,
                firstDay: firstDay,
                defaultDate: ea_settings.default_date,
                showWeek: ea_settings.show_week === '1',
                // add class to every field, so we can later find it
                beforeShowDay: function(date) {
                    var month = date.getMonth() + 1;
                    var days = date.getDate();

                    if(month < 10) {
                        month = '0' + month;
                    }

                    if(days < 10) {
                        days = '0' + days;
                    }

                    var dateString = date.getFullYear() + '-' + month + '-' + days;
                    var locationId = plugin.$element.find('[name="location"]').val();
                    var serviceId = plugin.$element.find('[name="service"]').val();
                    var workerId = plugin.$element.find('[name="worker"]').val();

                    // Filter connection working days
                    if (typeof ea_connections !== 'undefined' && Array.isArray(ea_connections) && ea_connections.length > 0) {
                        if (locationId && serviceId && workerId) {
                            var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            var dayName = dayNames[date.getDay()];
                            var isWorkingDay = false;

                            jQuery.each(ea_connections, function(i, conn) {
                                if (conn.location == locationId && conn.service == serviceId && conn.worker == workerId) {
                                    if (conn.day_from && dateString < conn.day_from) return true;
                                    if (conn.day_to && dateString > conn.day_to) return true;

                                    if (conn.day_of_week) {
                                        var daysArr = conn.day_of_week.split(',').map(function(s) { return s.trim(); });
                                        if (jQuery.inArray(dayName, daysArr) !== -1) {
                                            isWorkingDay = true;
                                            return false;
                                        }
                                    }
                                }
                            });

                            if (!isWorkingDay) {
                                return [false, 'not-working', 'Not Working'];
                            }
                        }
                    }

                    return plugin.vacation(workerId, dateString, serviceId);
                }
            });

            // hide options with one choiche
            this.hideDefault();

            // time is selected
            this.$element.find('.time').on('click', '.time-value', function (event) {
                event.preventDefault();

                var result = plugin.selectTimes(jQuery(this));

                plugin.triggerSlotSelectEvent();

                // check if we can select that field
                if (!result) {
                    if (ea_settings['trans.slot-not-selectable'] !== undefined) {
                        alert(ea_settings['trans.slot-not-selectable']);                        
                    }else{
                        alert('Not enough time please choose an earlier slot');
                    }
                    return;
                }

                if (ea_settings['pre.reservation'] === '1') {
                    plugin.appSelected.apply(plugin);
                } else {
                    // for booking overview
                    var booking_data = {};

                    booking_data.location = plugin.$element.find('[name="location"] > option:selected').text();
                    booking_data.service = plugin.$element.find('[name="service"] > option:selected').text();
                    booking_data.worker = plugin.$element.find('[name="worker"] > option:selected').text();
                    booking_data.date = plugin.$element.find('.date').datepicker().val();
                    booking_data.time = plugin.$element.find('.selected-time').data('val');
                    booking_data.price = plugin.$element.find('[name="service"] > option:selected').data('price');
                    booking_data.service_description = plugin.$element.find('[name="service"] > option:selected').data('description') || '';

                    var format = ea_settings['date_format'] + ' ' + ea_settings['time_format'];
                    booking_data.date_time = moment(booking_data.date + ' ' + booking_data.time, ea_settings['default_datetime_format']).format(format);

                    // set overview cancel_appointment
                    var overview_content = '';

                    overview_content = plugin.settings.overview_template({data: booking_data, settings: ea_settings});

                    plugin.$element.find('#booking-overview').html(overview_content);

                    plugin.$element.find('#ea-total-amount').on('checkout:done', function( event, checkoutId ) {
                        var paypal_input = plugin.$element.find('#paypal_transaction_id');

                        if (paypal_input.length == 0) {
                            paypal_input = jQuery('<input id="paypal_transaction_id" class="custom-field" name="paypal_transaction_id" type="hidden"/>');
                            plugin.$element.find('.final').append(paypal_input);
                        }

                        paypal_input.val(checkoutId);

                        // make final conformation
                        plugin.singleConformation(event);
                    });

                    // plugin.$element.find('.step').addClass('disabled');
                    plugin.$element.find('.final').removeClass('disabled');
                    plugin.scrollToElement(plugin.$element.find('.final'));
                    plugin.$element.find('#ea-payment-select').show();

                    // trigger global event when time slot is selected
                    jQuery(document).trigger('ea-timeslot:selected');
                }
            });

            // init blur next steps
            this.blurNextSteps(this.$element.find('.step:visible:first'), true);

            if (ea_settings['pre.reservation'] === '1') {
                this.$element.find('.ea-submit').on('click', jQuery.proxy(plugin.finalComformation, plugin));
            } else {
                this.$element.find('.ea-submit').on('click', jQuery.proxy(plugin.singleConformation, plugin));
            }

            this.$element.find('.ea-cancel').on('click', jQuery.proxy(plugin.cancelApp, plugin));

            setTimeout(function() {
                jQuery(document).trigger('ea-init:completed');
            }, 1000);
        },

        selectTimes: function ($element) {
            var plugin = this;

            if (ea_settings['is_multiple_booking_allowed'] == '1') {
                if ($element.hasClass('time-disabled')) {
                    return false;
                }
                $element.toggleClass('selected-time');
                return true;
            }

            var serviceData = plugin.$element.find('[name="service"] > option:selected').data();
            var duration = serviceData.duration;
            var slot_step = serviceData.slot_step;

            var takeSlots = parseInt(duration) / parseInt(slot_step);
            var $nextSlots = $element.nextAll();

            var forSelection = [];
            forSelection.push($element);

            if (($nextSlots.length + 1) < takeSlots) {
                return false;
            }

            $element.parent().children().removeClass('selected-time');

            jQuery.each($nextSlots, function (index, elem) {
                var $elem = jQuery(elem);

                var startTime = moment($element.data('val'), 'HH:mm');
                var calculatedTime = (index + 1) * slot_step;
                var expectedTime = startTime.add(calculatedTime, 'minutes').format('HH:mm');

                if ($elem.data('val') !== expectedTime) {
                    return false;
                }

                if (index + 2 > takeSlots) {
                    return false;
                }

                if ($elem.hasClass('time-disabled')) {
                    return false;
                }

                forSelection.push($elem);
            });

            if (forSelection.length < takeSlots) {
                return false;
            }

            jQuery.each(forSelection, function (index, elem) {
                elem.addClass('selected-time');
            });

            return true;
        },

        settingsOk: function () {
            var selectOptions = this.$element.find('select').not('.custom-field');
            var errors = jQuery('<div style="border: 1px solid gray; padding: 20px;">');
            var valid = true;

            selectOptions.each(function(index, element) {
                var $el = jQuery(element);
                var options = $el.children('option');

                // <option value="">-</option>
                if (options.length === 1 && options.attr('value') == '') {
                    jQuery(document.createElement('p'))
                        .html('You need to define at least one <strong>' + $el.attr('name') + '</strong>.')
                        .appendTo(errors);

                    valid = false;
                }
            });

            if (!valid) {
                errors.prepend('<h4>East Appointments - Settings validation:</h4>');
                errors.append('<p>There should be at least one Connection.</p>');

                this.$element.html(errors);
            }

            return valid;
        },
        hideDefault: function () {
            var steps = this.$element.find('.step');
            var count = 0;

            steps.each(function (index, element) {
                var select = jQuery(element).find('select');

                if (select.length < 1) {
                    return;
                }

                var options = select.children('option');

                if (options.length !== 1) {
                    return;
                }

                if (options.value !== '') {
                    jQuery(element).hide();
                    count++;
                }
            });

            if (count === 3) {
                this.settings.initScrollOff = true;
            }
        },
        // get All previus step options
        getPrevousOptions: function (element) {
            var step = element.parents('.step');

            var options = {};

            var data_prev = step.prevAll('.step');

            data_prev.each(function (index, elem) {
                var option = jQuery(elem).find('select,input').first();

                options[jQuery(option).data('c')] = option.val();
            });

            return options;
        },
        /**
         * Get next select option
         */
        getNextOptions: function (event) {
            var current = jQuery(event.target);
            var currentCategory = current.data('c');
            var isLocationOrService = (currentCategory === 'location' || currentCategory === 'service');

            var step = current.parent('.step');

            // blur next options
            this.blurNextSteps(step, isLocationOrService);

            // nothing selected
            if (current.val() === '') {
                return;
            }

            var options = {};

            options[currentCategory] = current.val();

            var data_prev = step.prevAll('.step');

            data_prev.each(function (index, elem) {
                var input_field = jQuery(elem).find('.filter').filter('input, select');

                options[jQuery(input_field).data('c')] = input_field.val();
            });

            // hidden
            this.$element.find('.step:hidden').each(function (index, elem) {
                var option = jQuery(elem).find('select,input').first();

                options[jQuery(option).data('c')] = option.val();
            });

            //only visible step
            var nextStep = step.nextAll('.step:visible:first');

            var next = jQuery(nextStep).find('select,input');

            if (next.length === 0) {
                this.blurNextSteps(nextStep, isLocationOrService);
                if (currentCategory === 'worker') {
                    this.scrollToElement(this.$element.find('.date'));
                }
                return;
            }

            options.next = next.data('c');

            this.callServer(options, next);
        },
        /**
         * Standard call for select options (location, service, worker)
         */
        callServer: function (options, next_element) {
            var plugin = this;

            options.action = 'ea_next_step';
            options.check  = ea_settings['check'];
            options._cb    = Math.floor(Math.random() * 1000000);

            this.placeLoader(next_element.parent());

            jQuery.get(ea_ajaxurl, options, function (response) {
                next_element.empty();
                var default_option_value = '-';
                if (options.next == 'service') {
                    default_option_value = ea_settings['trans.service_option'];
                }
                if (options.next == 'location') {
                    default_option_value = ea_settings['trans.location_option'];
                }
                if (options.next == 'worker') {
                    default_option_value = ea_settings['trans.worker_option'];
                }
                // default
                next_element.append('<option value="">'+default_option_value+'</option>');

                // options
                jQuery.each(response, function (index, element) {
                    var name = element.name;
                    var $option = jQuery('<option value="' + element.id + '">' + name + '</option>');

                    if ('price' in element && ea_settings['price.hide'] !== '1') {
                        if (ea_settings['hide.decimal_in_price'] == '1' && !isNaN(element.price)) {
                            element.price = Math.round(element.price);
                        }

                        if (ea_settings['currency.before'] == '1') {
                            $option.text(element.name + ' - ' + next_element.data('currency') + element.price);
                        } else {
                            $option.text(element.name + ' - ' + element.price + next_element.data('currency'));
                        }

                        $option.data('price', element.price);
                    }

                    if ('slot_step' in element) {
                        $option.data('slot_step', element.slot_step);
                        $option.data('duration', element.duration);
                    }

                    next_element.append($option);
                });

                // enabled
                next_element.parent().removeClass('disabled');

                plugin.removeLoader();

                // Only auto-scroll when moving past worker step to calendar
                if (options.next !== 'service' && options.next !== 'worker') {
                    plugin.scrollToElement(next_element.parent());
                }
            }, 'json')
            .error(function(xhr, status) {

                if (xhr.status === 403) {
                    alert(ea_settings['trans.nonce-expired']);
                }

                if (xhr.status === 500) {
                    alert(ea_settings['trans.internal-error']);
                }

                plugin.removeLoader();
            });
        },
        placeLoader: function ($element) {
            if (!$element || !$element.length || $element.is(':hidden')) {
                var fieldName = ($element && $element.length) ? $element.find('select, input').data('c') : '';
                var $visibleGrp = fieldName ? this.$element.find('.ea-new-ui-field-group[data-field="' + fieldName + '"]') : jQuery();
                if ($visibleGrp.length) {
                    $element = $visibleGrp;
                } else {
                    $element = this.$element.find('.time').length ? this.$element.find('.time') : this.$element.find('.date');
                }
            }
            if ($element && $element.length) {
                $element.css('position', 'relative');
            }
            var $loader = jQuery('#ea-loader');
            if (!$loader.length) {
                $loader = jQuery('<div id="ea-loader"></div>').appendTo(this.$element);
            }
            if ($element && $element.length) {
                $loader.appendTo($element);
            }
            $loader.css({
                'position': 'absolute',
                'top': 0,
                'left': 0,
                'width': '100%',
                'height': '100%',
                'min-height': '35px',
                'z-index': 100,
                'display': 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                'background': 'rgba(255, 255, 255, 0.7)',
                'border-radius': '8px'
            }).show();
        },
        removeLoader: function () {
            jQuery('#ea-loader').hide();
        },
        getCurrentStatus: function () {
            var options = jQuery(this.element).find('select');
        },
        blurNextSteps: function (current, dontScroll) {
            // check if there is scroll param
            dontScroll = dontScroll || false;

            current.removeClass('disabled');

            var nextSteps = current.nextAll('.step:visible');

            nextSteps.each(function (index, element) {
                jQuery(element).addClass('disabled');
            });

            // if next step is calendar
            if (current.hasClass('calendar')) {

                var calendar = this.$element.find('.date');

                this.$element.find('.ui-datepicker-current-day').click();

                if (!dontScroll) {
                    this.scrollToElement(calendar);
                }
            }
        },
        /**
         * Change of date - datepicker
         */
        dateChange: function (dateString, calendar) {
            var plugin = this;

            calendar = jQuery(calendar.dpDiv).parents('.date');

            calendar.parent().next().addClass('disabled');

            var options = this.getPrevousOptions(calendar);

            options.action = 'ea_date_selected';
            options.date   = dateString;
            options.check  = ea_settings['check'];
            options._cb    = Math.floor(Math.random() * 1000000);

            this.placeLoader(calendar);

            jQuery.get(ea_ajaxurl, options, function (response_m) {
                var response = response_m.calendar_slots;

                var next_element = jQuery(calendar).parent().next('.step').children('.time');

                var fromTo = ea_settings["label.from_to"] == "1";

                jQuery.each(response, function (index, element) {
                    var classAMPM = (ea_settings["time_format"] == "am-pm") ? ' am-pm' : '';
                    var displayTime = fromTo ? element.show + ' - ' + element.ends : element.show;

                    var isDisabled = false;
                    var tooltip_title = "";
                    if (window.ea_partial_vacations && window.ea_partial_vacations.length > 0) {
                        var selectedWorker = plugin.$element.find('[name="worker"] option:selected, .ea-filters-grid select[data-c="worker"] option:selected').filter(':selected').val() || plugin.$element.find('[name="worker"]').val();
                        var $selectedService = plugin.$element.find('[name="service"] option:selected, .ea-filters-grid select[data-c="service"] option:selected').filter(':selected');
                        var serviceDuration = parseInt($selectedService.data('duration')) || parseInt(plugin.$element.find('[name="service"]').find('option:selected').data('duration')) || 0;

                        window.ea_partial_vacations.forEach(function(vac) {
                            if (vac.day === dateString && vac.workerId == selectedWorker) {
                                var appointmentStart = moment(element.value, 'HH:mm');
                                var appointmentEnd = appointmentStart.clone().add(serviceDuration, 'minutes');
                                var start = moment(vac.start, 'HH:mm');
                                var end = moment(vac.end, 'HH:mm');

                                if (appointmentStart.isBefore(end) && appointmentEnd.isAfter(start)) {
                                    tooltip_title = vac.tooltip || '';
                                    isDisabled = true;
                                }
                            }
                        });
                    }

                    if (!tooltip_title && typeof ea_vacations !== 'undefined' && Array.isArray(ea_vacations)) {
                        var selWorker = plugin.$element.find('[name="worker"]').val();
                        jQuery.each(ea_vacations, function(idx, v) {
                            if (v.days && jQuery.inArray(dateString, v.days) !== -1) {
                                if (v.workers && v.workers.length > 0) {
                                    var wIds = jQuery.map(v.workers, function(w) { return w.id; });
                                    if (jQuery.inArray(selWorker, wIds) !== -1) {
                                        tooltip_title = v.tooltip || '';
                                        return false;
                                    }
                                }
                            }
                        });
                    }

                    if (element.count > 0 && !isDisabled) {
                        // show remaining slots or not
                        if (ea_settings['show_remaining_slots'] === '1') {
                            next_element.append('<a href="#" class="time-value slots' + classAMPM + '" data-val="' + element.value + '">' + displayTime + ' (' + element.count + ')</a>');
                        } else {
                            next_element.append('<a href="#" class="time-value' + classAMPM + '" data-val="' + element.value + '">' + displayTime + '</a>');
                        }
                    } else {
                        if (ea_settings['show_remaining_slots'] === '1') {
                            next_element.append('<a class="time-disabled slots' + classAMPM + '" title="' + tooltip_title + '">' + displayTime + ' (0)</a>');
                        } else {
                            next_element.append('<a class="time-disabled' + classAMPM + '" title="' + tooltip_title + '">' + displayTime + '</a>');
                        }
                    }

                });

                if (response.length === 0) {
                    next_element.html('<p class="time-message">' + ea_settings['trans.please-select-new-date'] + '</p>');
                }

                // enabled
                next_element.parent().removeClass('disabled');

                if (!plugin.settings.initScrollOff) {
                    next_element.find('.time-value:first').focus();
                } else {
                    plugin.settings.initScrollOff = false;
                }

            }, 'json')
                .always(function () {
                    plugin.removeLoader();
                });
        },
        /**
         * Appintment information - before user add personal
         * information
         */
        appSelected: function (element) {
            var plugin = this;

            this.placeLoader(this.$element.find('.selected-time'));

            // make pre reservation
            var options = {
                location: this.$element.find('[name="location"]').val(),
                service: this.$element.find('[name="service"]').val(),
                worker: this.$element.find('[name="worker"]').val(),
                date: this.$element.find('.date').datepicker().val(),
                end_date: this.$element.find('.date').datepicker().val(),
                start: this.$element.find('.selected-time').data('val'),
                check: ea_settings['check'],
                action: 'ea_res_appointment',
                _cb: Math.floor(Math.random() * 1000000)
            };

            // for booking overview
            var booking_data = {};
            booking_data.location = this.$element.find('[name="location"] > option:selected').text();
            booking_data.service = this.$element.find('[name="service"] > option:selected').text();
            booking_data.worker = this.$element.find('[name="worker"] > option:selected').text();
            booking_data.date = this.$element.find('.date').datepicker().val();
            booking_data.time = this.$element.find('.selected-time').data('val');
            booking_data.price = this.$element.find('[name="service"] > option:selected').data('price');
            booking_data.service_description = this.$element.find('[name="service"] > option:selected').data('description') || '';

            var format = ea_settings['date_format'] + ' ' + ea_settings['time_format'];
            booking_data.date_time = moment(booking_data.date + 'T' + booking_data.time, ea_settings['default_datetime_format']).format(format);

            jQuery.get(ea_ajaxurl, options, function (response) {

                plugin.res_app = response.id;

                plugin.$element.find('.ea-cancel').data('_hash', response._hash);

                plugin.$element.find('.step').addClass('disabled');
                plugin.$element.find('.final').removeClass('disabled');

                plugin.$element.find('.final').find('select,input').first().focus();

                plugin.scrollToElement(plugin.$element.find('.final'));
                // set overview cancel_appointment
                var overview_content = '';

                overview_content = plugin.settings.overview_template({data: booking_data, settings: ea_settings});

                jQuery('#booking-overview').html(overview_content);

                plugin.$element.find('#ea-total-amount').on('checkout:done', function( event, checkoutId ) {
                    var paypal_input = plugin.$element.find('#paypal_transaction_id');

                    if (paypal_input.length == 0) {
                        paypal_input = jQuery('<input id="paypal_transaction_id" class="custom-field" name="paypal_transaction_id" type="hidden"/>');
                        plugin.$element.find('.final').append(paypal_input);
                    }

                    paypal_input.val(checkoutId);

                    // make final conformation
                    plugin.finalComformation(event);
                });

            }, 'json')
                .fail(function (response) {
                    alert(response.responseJSON.message);
                })
                .always(jQuery.proxy(function () {
                    this.removeLoader();
                }, plugin));
        },
        /**
         * Comform appointment
         */
        finalComformation: function (event) {
            event.preventDefault();

            var plugin = this;

            var form = this.$element.find('form');

            if (!form.valid()) {
                return;
            }

            this.$element.find('.ea-submit').prop('disabled', true);

            // make pre reservation
            var options = {
                id: this.res_app,
                check: ea_settings['check']
            };

            this.$element.find('.custom-field').not('.dummy').each(function (index, element) {
                var name = jQuery(element).attr('name');
                options[name] = jQuery(element).val();
            });

            options.action = 'ea_final_appointment';
            options._cb    = Math.floor(Math.random() * 1000000);

            jQuery.get(ea_ajaxurl, options, function (response) {
                plugin.isSubmitting = false;

                // Remove loader spinner, update button text to Booked, and hide cancel button
                jQuery('.ea-submit, .booking-button')
                    .removeClass('ea-loading')
                    .prop('disabled', true)
                    .html('<span>Booked</span>');
                jQuery('.ea-cancel').hide();
                plugin.$element.find('.ea-cancel').hide();
                plugin.$element.find('#paypal-button').hide();

                if (ea_settings['show.display_thankyou_note'] == 1) {                    
                    plugin.$element.find('.step').hide();
                    var table_html = plugin.$element.find('#booking-overview').find('table').html();
                    plugin.$element.find('#booking-overview').show();
                    plugin.$element.find('#booking-overview').find('table').hide();
                    plugin.$element.find('.final').show();
                    plugin.$element.find('.ea_hide_show').hide();
                    plugin.$element.find('.ea-confirmation-subtext').hide();
                    plugin.$element.find('#booking-overview-header').hide();
                    plugin.$element.find('#ea-overview-message').hide();
                    plugin.$element.find('#ea-success-box').show();
                    plugin.$element.find('#ea-overview-details').html(table_html);
    
                    const meta = document.getElementById('ea-meta-data');
                    if (meta) {
                        const rawDateTime = meta.dataset.dateTime;
                        const service = meta.dataset.service;
                        const worker = meta.dataset.worker;
                        const location = meta.dataset.location;
                        const price = document.getElementById('ea-total-amount')?.dataset.price || '';
                        const currency = meta.dataset.currency;
                        const title = `${service} with ${worker}`;
                        const description = `Service: ${service}\nWorker: ${worker}\nPrice: ${price}${currency}`;
                        const startDateObj = new Date(rawDateTime);
                        if (isNaN(startDateObj.getTime())) {
                            console.error('Invalid date:', rawDateTime);
                        }else{
                            const endDateObj = new Date(startDateObj.getTime() + 60 * 60 * 1000); // +1 hour
        
                            const formatDateForGoogle = (dateObj) =>
                                dateObj.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
        
                            const start = formatDateForGoogle(startDateObj);
                            const end = formatDateForGoogle(endDateObj);
        
                            const calendarUrl = new URL("https://calendar.google.com/calendar/render");
                            calendarUrl.searchParams.set("action", "TEMPLATE");
                            calendarUrl.searchParams.set("text", title);
                            calendarUrl.searchParams.set("dates", `${start}/${end}`);
                            calendarUrl.searchParams.set("details", description);
                            calendarUrl.searchParams.set("location", location);
                            calendarUrl.searchParams.set("trp", "false");
        
                            document.getElementById("ea-add-to-calendar").href = calendarUrl.toString();
                        }
    
                    }
                    
                    plugin.$element.find('.ea-status-note').text(ea_settings['default_status_message']);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }else{
                    plugin.$element.find('.final').append('<h3 class="ea-done-message">' + _.escape(ea_settings['trans.done_message']) + '</h3>');
                }


                plugin.$element.find('form').find('input').prop('disabled', true);
                plugin.$element.find('.g-recaptcha').remove();

                // send an event
                plugin.triggerEvent();

                if (ea_settings['submit.redirect'] !== '') {
                    setTimeout(function () {
                        window.location.href = ea_settings['submit.redirect'];
                    }, 2000);
                }
            }, 'json')
                .fail(jQuery.proxy(function () {
                    this.$element.find('.ea-submit').prop('disabled', false);
                }, plugin));
        },
        singleConformation: function (event) {
            event.preventDefault();
            var plugin = this;

            var form = this.$element.find('form');

            if (!form.valid()) {
                return;
            }

            this.$element.find('.ea-submit').prop('disabled', true);

            // make pre reservation
            var options = {
                location: this.$element.find('[name="location"]').val(),
                service: this.$element.find('[name="service"]').val(),
                worker: this.$element.find('[name="worker"]').val(),
                date: this.$element.find('.date').datepicker().val(),
                end_date: this.$element.find('.date').datepicker().val(),
                start: this.$element.find('.selected-time').data('val'),
                check: ea_settings['check'],
                action: 'ea_res_appointment'
            };

            if (this.$element.find('.g-recaptcha-response').length === 1) {
                options.captcha = this.$element.find('.g-recaptcha-response').val();
            }

            options._cb    = Math.floor(Math.random() * 1000000);

            jQuery.get(ea_ajaxurl, options, function (response) {
                plugin.res_app = response.id;

                plugin.finalComformation(event);
            }, 'json')
                .fail(jQuery.proxy(function (response) {
                    alert(response.responseJSON.message);
                    this.$element.find('.ea-submit').prop('disabled', false);
                }, plugin))
                .always(jQuery.proxy(function () {
                    this.removeLoader();
                }, plugin));
        },
        triggerEvent: function () {
            // Create the event.
            var event = document.createEvent('Event');

            // Define that the event name is 'easyappnewappointment'.
            event.initEvent('easyappnewappointment', true, true);

            // send event to document
            document.dispatchEvent(event);
        },
        /**
         * Event when customer select time slot
         */
        triggerSlotSelectEvent: function () {
            // Create the event.
            var event = new Event('easyappslotselect');

            // send event to document
            document.dispatchEvent(event);
        },
        /**
         * Cancel appointment
         */
        resetForm: function () {
            var plugin = this;

            var $form = plugin.$element.find('form');
            if ($form.length && $form[0] && $form[0].reset) {
                $form[0].reset();
            }

            plugin.$element.find('select.filter, select.custom-field, .ea-new-ui select').each(function() {
                var $select = jQuery(this);
                var firstVal = $select.find('option:first').val() || '';
                $select.val(firstVal);
            });

            plugin.$element.find('#ea-service-description').empty().hide();
            plugin.$element.find('.selected-time').removeClass('selected-time');
            plugin.$element.find('.time').empty();
            plugin.$element.find('.time-row').remove();

            var $date = plugin.$element.find('.date');
            if ($date.length && $date.datepicker) {
                try {
                    $date.datepicker('setDate', null);
                    $date.find('.ui-datepicker-current-day').removeClass('ui-datepicker-current-day');
                } catch (e) {}
            }

            if ($form.length && $form.data('validator')) {
                $form.data('validator').resetForm();
            }

            plugin.$element.find('#booking-overview').empty();

            var $bar = plugin.$element.find('.ea-booking-summary-bar');
            if ($bar.length) {
                $bar.find('.ea-summary-text').text('Select a date & time to continue').addClass('empty');
                $bar.find('.ea-submit, .ea-cancel').hide();
            }

            plugin.res_app = null;
            plugin.blurNextSteps(plugin.$element.find('.step:visible:first'), true);
        },

        cancelApp: function (event) {
            if (event) event.preventDefault();
            var plugin = this;

            var currentResApp = plugin.res_app;
            var _hash = plugin.$element.find('.ea-cancel').data('_hash');

            plugin.resetForm();

            if (ea_settings['pre.reservation'] === '1' && currentResApp) {
                var options = {
                    id: currentResApp,
                    check: ea_settings['check'],
                    _hash: _hash,
                    action: 'ea_cancel_appointment',
                    _cb: Math.floor(Math.random() * 1000000)
                };

                jQuery.get(ea_ajaxurl, options, function (response) {}, 'json');
            }

            return false;
        },
        chooseStep: function () {
            var plugin = this;
            var $temp;

            switch (ea_settings['cancel.scroll']) {
                case 'calendar':
                    plugin.scrollToElement(plugin.$element.find('.date'));
                    break;
                case 'worker' :
                    $temp = plugin.$element.find('[name="worker"]');
                    $temp.val('');
                    $temp.change();
                    plugin.scrollToElement($temp);
                    break;
                case 'service' :
                    $temp = plugin.$element.find('[name="service"]');
                    $temp.val('');
                    $temp.change();
                    plugin.scrollToElement($temp);
                    break;
                case 'location' :
                    $temp = plugin.$element.find('[name="location"]');
                    $temp.val('');
                    $temp.change();
                    plugin.scrollToElement($temp);
                    break;
                case 'pagetop':
                    break;
            }
        },
        scrollToElement: function (element) {
            if (ea_settings.scroll_off === 'true') {
                return;
            }

            jQuery('html, body').animate({
                scrollTop: ( element.offset().top - 20 )
            }, 500);
        },

        parsePhoneField: function ($el) {
            var code = $el.parent().find('.ea-phone-country-code-part').val();
            var number = $el.parent().find('.ea-phone-number-part').val().replace(/^0+/, '');

            $el.parent().find('.full-value').val('+' + code + number);
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    jQuery.fn[pluginName] = function (options) {
        this.each(function () {
            if (!jQuery.data(this, "plugin_" + pluginName)) {
                jQuery.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
        // chain jQuery functions
        return this;
    };
})( jQuery, window, document );


jQuery(document).ready(function($){
    jQuery('.ea-standard').eaStandard();
});