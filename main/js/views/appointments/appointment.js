/**
 * 
 */
EA.AppointmentView = Backbone.View.extend({
    
    tagName: "tr",

    // show template
    template_show : _.template( jQuery("#ea-tpl-appointment-row").html() ),
    
    // edit template
    template_edit : _.template( jQuery("#ea-tpl-appointment-row-edit").html() ),

    // select times template
    template_times : _.template( jQuery("#ea-tpl-appointment-times").html() ),

    template : null,

    edit_mode : false,

    events: {
        "click .btn-edit"   : "edit",
        "click .btn-clone"  : "clone",
        "dblclick"          : "edit",
        "click .btn-del"    : "removeItem",
        "click .btn-save"   : "save",
        "click .btn-cancel" : "cancel",
        "keydown input"     : "keydownEvent",
        "keydown select"    : "keydownEvent",
        "change .app-fields": "changeApp",
        "change .time-start": "setEndTimeApp",
        "change .ea-service": "serviceChange",

        "change #ea-input-locations": "locationChanged",
        "change #ea-input-services" : "serviceChanged"
    },

    initialize: function () {
        this.template = this.template_show;
    },

    render: function () {
        var self = this;

        var renderedContent = this.template( { 
            row       : this.model.toJSON(),
            cache     : eaData
        } );

        jQuery(this.el).html( renderedContent );

        this.setDefaults();

        this.$el.addClass('ea-row');

        this.locationChanged();
        this.serviceChanged();

        return this;
    },

    /**
     * Set defaults if there are one
     */
    setDefaults: function() {
        if (eaData.Locations.length === 1) {
            this.$el.find('#ea-input-locations').val(eaData.Locations[0].id);
        }

        if (eaData.Services.length === 1) {
            this.$el.find('#ea-input-services').val(eaData.Services[0].id);
            this.serviceChange();
        }

        if (eaData.Workers.length === 1) {
            this.$el.find('#ea-input-workers').val(eaData.Workers[0].id);
        }

    },

    /**
     *
     */
    locationChanged: function() {
        var location = this.$el.find('#ea-input-locations').val();

        // enabled all the fields
        this.$el.find('#ea-input-services').children().prop("disabled", false).show();
        this.$el.find('#ea-input-workers').children().prop("disabled", false).show();

        if (location === '') {
            return;
        }

        var services = [];
        var workers = [];

        jQuery.each(ea_connections, function(index, connection) {
            if (connection.location === location) {
                if (_.indexOf(services, connection.service) === -1 ) {
                    services.push(connection.service);
                }

                if (_.indexOf(workers, connection.worker) === -1 ) {
                    workers.push(connection.worker);
                }
            }
        });

        this.$el.find('#ea-input-services').children().each(function(index, element) {
            var value = jQuery(element).attr('value');

            if (value === '') {
                return;
            }

            if (_.indexOf(services, value) === -1 ) {
                jQuery(element).prop('disabled', true).hide();
            }
        });

        this.$el.find('#ea-input-workers').children().each(function(index, element) {
            var value = jQuery(element).attr('value');

            if (value === '') {
                return;
            }

            if (_.indexOf(workers, value) === -1 ) {
                jQuery(element).prop('disabled', true).hide();
            }
        });
    },

    serviceChanged: function() {
        var location = this.$el.find('#ea-input-locations').val();
        var service = this.$el.find('#ea-input-services').val();

        // enabled all the fields
        this.$el.find('#ea-input-workers').children().prop("disabled", false).show();

        var workers = [];

        jQuery.each(ea_connections, function(index, connection) {
            if (connection.location === location && connection.service === service) {
                if (_.indexOf(workers, connection.worker) === -1 ) {
                    workers.push(connection.worker);
                }
            }
        });

        this.$el.find('#ea-input-workers').children().each(function(index, element) {
            var value = jQuery(element).attr('value');

            if (value === '') {
                return;
            }

            if (_.indexOf(workers, value) === -1 ) {
                jQuery(element).prop('disabled', true).hide();
            }
        });
    },

    /**
     *
     */
    edit: function() {
        var self = this;

        if(this.edit_mode) {
            return;
        }

        if(this.$el.hasClass('ea-editing')) {
            return;
        }
        
        // Edit class
        this.$el.addClass('ea-editing');

        this.template = this.template_edit;
        this.render();

        this.$el.find('select, input').first().focus();

        // this.$el.find('[data-prop="start"]').timepicker();
        var datepickerElement = this.$el.find('[data-prop="date"]');

        datepickerElement.datepicker({
            dateFormat: jQuery.datepicker.regional[ea_settings.datepicker].dateFormat,
            minDate: 0
        });

        datepickerElement.datepicker("setDate", moment(this.model.get('date'), "YYYY-MM-DD").toDate());

        this.changeApp();

        this.edit_mode = true;
    },

    /**
     * Clone object
     * @param e
     */
    clone: function(e) {
        e.preventDefault();

        var collection = this.model.collection;
        var appointment = this.model.clone();

        appointment.unset('_id');
        appointment.unset('id');
        appointment.unset('start');
        appointment.unset('end');

        collection.add(appointment, {at: 0});

        var appointmentView = new EA.AppointmentView({
            model: appointment
        });

        appointmentView.setData(
            this.locations,
            this.services,
            this.workers
        );

        this.$el.closest("#ea-appointments").prepend(appointmentView.$el);

        appointmentView.edit();
    },

    save: function() {
        var appointment = this.model;
        var view = this;
        var customParams = {};

        this.$el.find('.time-start').change();

        jQuery.each(this.$el.find('input, select, textarea'), function(index, elem) {
            var $elem = jQuery(elem);

            if ($elem.data('prop') === 'date') {
                appointment.set($elem.data('prop'), moment(jQuery(elem).datepicker('getDate')).format('YYYY-MM-DD'));
                appointment.set('end_date', moment(jQuery(elem).datepicker('getDate')).format('YYYY-MM-DD'));
            } else {
                if (!$(elem).is(':disabled')) {
                    appointment.set($elem.data('prop'), $elem.val());
                }
            }

            if($elem.attr('name') === 'send-mail' && $elem.is(':checked')) {
                customParams._mail = $elem.val();
            }
        });

        // Saves appointment
        appointment.save(customParams, {
            success: function(model, response) {
                view.render();
            }
        });

        this.$el.removeClass('ea-editing');

        // show row
        this.template = this.template_show;

        this.render();

        this.edit_mode = false;
    },

    cancel: function() {
        // If is new remove model/view
        if(this.model.isNew()) {
        
            this.model.destroy();
            this.remove();
        
        } else {

            this.$el.removeClass('ea-editing');

            this.template = this.template_show;
            this.render();
        }

        this.edit_mode = false;
    },

    // Deletes model and view
    removeItem: function() {
        var view = this;

        if (!confirm('Are you sure?')) {
            return;
        }

        this.model.destroy({
            success: function(model, response) {
                view.remove();
            }
        });
    },

    setData: function(locations, services, workers) {
        this.locations = locations;
        this.services  = services;
        this.workers   = workers;
    },

    // 
    keydownEvent: function(e) {
        switch (e.which) {
            // esc
            case 27 :
                this.cancel();
            break;
        }
    },

    /**
     * Change of App params
     */
    changeApp: function() {

        var fields = this.$el.find(".app-fields");
        var timeField = this.$el.find('[data-prop="start"]');

        // remove current times
        timeField.empty();

        var isComplete = true;

        var filter = {};

        jQuery.each(fields, function(index, element){
            var value = jQuery(element).val();

            filter[jQuery(element).data('prop')] = value;

            // format date field
            if (jQuery(element).data('prop') === 'date') {
                filter['date'] = moment(jQuery(element).datepicker('getDate')).format('YYYY-MM-DD');
            }

            if(value === '') {
                isComplete = false;
            }
        });

        if(isComplete) {
            filter.action = 'ea_open_times';
            filter.app_id = this.model.get('id');

            var that = this;
            
            jQuery.get(
                ajaxurl,
                filter, 
                function(response) {
                    if(response.length > 0) {
                        // console.log(response);
                        var options = that.template_times({
                            app : that.model.toJSON(),
                            times: response
                        });

                        timeField.html(options);
                        timeField.prop('disabled', false);
                    }
            }, "json");
        } else {
            timeField.prop('disabled', true);
        }
    },

    setEndTimeApp: function() {
        var start = this.$el.find('.time-start').val();
        var date = this.$el.find('.date-start').val();

        // service duration
        var service = this.$el.find('[name="ea-input-services"]');
        var duration = parseInt(service.children(':selected').data('duration'));

        var startTime = new Date(date + "T" + start);

        var newDateObj = new Date(startTime.getTime() + duration * 60000);

        var minutes = newDateObj.getMinutes();
        var hours = newDateObj.getHours();

        if(minutes.length === 1) {
            minutes = '0' + minutes;
        }

        if(hours.length === 1) {
            hours = '0' + hours;
        }
        // FIX there is time end issue here
        // this.model.set('end', hours + ":" + minutes);
        this.model.set('end', null);
    },

    serviceChange: function() {
        if (!this.model.isNew()) {
            return;
        }

        var option = this.$el.find('.ea-service').children(':selected');

        this.$el.find('.ea-price').val(option.data('price'));
    }
});