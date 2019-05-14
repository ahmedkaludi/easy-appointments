/**
 * Main Admin View
 * Renders Admin tab panel
 *
 **/
EA.MainView = Backbone.View.extend({
    el: jQuery('#wpbody-content'),

    template: _.template(jQuery("#ea-appointments-main").html()),

    events: {
        "change .filter-part input": "filterChange",
        "change .filter-part select": "filterChange",
        "click .refresh-list": "refreshList",
        "click .add-new": "addNew",

        "change #ea-filter-locations": "filterLocationChanged",
        "change #ea-filter-services" : "filterServiceChanged"
    },

    initialize: function () {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ea_settings.datepicker]);

        // Empty array of connections
        this.collection = new EA.Appointments();

        if (typeof eaData !== 'undefined') {
            // In page cache
            this.locations = new EA.Locations(eaData.Locations);
            this.services = new EA.Services(eaData.Services);
            this.workers = new EA.Workers(eaData.Workers);
        } else {
            // Get from server
            this.locations = new EA.Locations();
            this.services = new EA.Services();
            this.workers = new EA.Workers();

            this.locations.fetch();
            this.services.fetch();
            this.workers.fetch();
        }

        this.render();

        this.setDefaults();

        // Bind the reset event
        this.collection.bind("reset", this.showRows, this);

        // Get data from server
        // this.collection.fetch( {reset:true} );
        this.filterChange();
    },

    /**
     * Set defaults if there are one
     */
    setDefaults: function () {
        if (this.locations.length === 1) {
            this.$el.find('#ea-filter-locations').val(this.locations.at(0).get('id'));
        }

        if (this.services.length === 1) {
            this.$el.find('#ea-filter-services').val(this.services.at(0).get('id'));
        }

        if (this.workers.length === 1) {
            this.$el.find('#ea-filter-workers').val(this.workers.at(0).get('id'));
        }
    },

    render: function () {
        this.$el.empty();

        this.$el.html(this.template({cache: eaData}));

        // From datepicker
        this.$el.find('#ea-filter-from').datepicker({
            dateFormat: jQuery.datepicker.regional[ea_settings.datepicker].dateFormat
        });

        this.$el.find('#ea-filter-from').datepicker('setDate', this.getMonday(new Date()));

        // To datepicker
        this.$el.find('#ea-filter-to').datepicker({
            dateFormat: jQuery.datepicker.regional[ea_settings.datepicker].dateFormat
        });

        this.$el.find('#ea-filter-to').datepicker('setDate', this.getSunday(new Date()));

        this.showRows();

        return this;
    },

    showRows: function () {
        var self = this; // so you can use this inside the each function

        var row_container = self.$el.find("#ea-appointments");

        row_container.empty();

        this.collection.each(function (appointment) { // iterate through the collection
            var appointmentView = new EA.AppointmentView({
                model: appointment
            });

            appointmentView.setData(
                self.locations,
                self.services,
                self.workers
            );

            appointmentView.render();

            row_container.append(appointmentView.$el);
        });

        this.showMessage('');
    },

    // get current Filter
    getFilter: function () {
        var filters = this.$el.find('input, select');

        var filter = {};

        jQuery.each(filters, function (index, elem) {
            var value = jQuery(elem).val();
            var col = jQuery(elem).data('c');

            if (value !== '') {

                if (col === 'from') {
                    value = moment(jQuery(elem).datepicker('getDate')).format('YYYY-MM-DD');
                } else if (col === 'to') {
                    value = moment(jQuery(elem).datepicker('getDate')).format('YYYY-MM-DD');
                }

                filter[col] = value;
            }
        });

        return filter;
    },

    // Filter has changed
    filterChange: function (e) {
        if (typeof e !== 'undefined' && jQuery(e.currentTarget).is('#ea-period')) {
            switch (jQuery(e.currentTarget).val()) {
                case 'week':
                    this.setThisWeekPeriod();
                    break;
                case 'month':
                    this.setThisMonthPeriod();
                    break;
                case 'today':
                    this.setThisDayPeriod();
                    break;
                default:
                    return;
            }
        }

        var filter = this.getFilter();
        var that = this;

        this.showMessage('Loading table...', true);

        this.collection.fetch({data: jQuery.param(filter), reset: true}, {
            error: function (response) {
                that.showMessage('');
                alert('Error, try refresh again.');
            }
        });
    },

    /**
     *
     */
    filterLocationChanged: function() {
        var location = this.$el.find('#ea-filter-locations').val();

        // enabled all the fields
        this.$el.find('#ea-filter-services').children().prop("disabled", false).show();
        this.$el.find('#ea-filter-workers').children().prop("disabled", false).show();

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

        this.$el.find('#ea-filter-services').children().each(function(index, element) {
            var value = jQuery(element).attr('value');

            if (value === '') {
                return;
            }

            if (_.indexOf(services, value) === -1 ) {
                jQuery(element).prop('disabled', true).hide();
            }
        });

        this.$el.find('#ea-filter-workers').children().each(function(index, element) {
            var value = jQuery(element).attr('value');

            if (value === '') {
                return;
            }

            if (_.indexOf(workers, value) === -1 ) {
                jQuery(element).prop('disabled', true).hide();
            }
        });
    },

    filterServiceChanged: function() {
        var location = this.$el.find('#ea-filter-locations').val();
        var service = this.$el.find('#ea-filter-services').val();

        // enabled all the fields
        this.$el.find('#ea-filter-workers').children().prop("disabled", false).show();

        var workers = [];

        jQuery.each(ea_connections, function(index, connection) {
            if (connection.location === location && connection.service === service) {
                if (_.indexOf(workers, connection.worker) === -1 ) {
                    workers.push(connection.worker);
                }
            }
        });

        this.$el.find('#ea-filter-workers').children().each(function(index, element) {
            var value = jQuery(element).attr('value');

            if (value === '') {
                return;
            }

            if (_.indexOf(workers, value) === -1 ) {
                jQuery(element).prop('disabled', true).hide();
            }
        });
    },

    addNew: function (e) {
        e.preventDefault();

        var appointment = new EA.Appointment();

        var location = this.$el.find('#ea-filter-locations').val();
        var service = this.$el.find('#ea-filter-services').val();
        var worker = this.$el.find('#ea-filter-workers').val();

        if (location !== '') {
            appointment.set('location', location);
        }

        if (service !== '') {
            appointment.set('service', service);
        }

        if (worker !== '') {
            appointment.set('worker', worker);
        }

        this.collection.add(appointment, {at: 0});

        var appointmentView = new EA.AppointmentView({
            model: appointment
        });

        appointmentView.setData(
            this.locations,
            this.services,
            this.workers
        );

        this.$el.find("#ea-appointments").prepend(appointmentView.$el);

        appointmentView.edit();
    },

    /**
     * Refresh list
     */
    refreshList: function (e) {
        e.preventDefault();

        this.filterChange();
    },

    getMonday: function (d) {
        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
        return new Date(d.setDate(diff));
    },

    getSunday: function (d) {
        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() + (day == 0 ? 0 : (7 - day)); // adjust when day is sunday
        return new Date(d.setDate(diff));
    },

    showMessage: function (text, hold) {
        var onHold = hold || false;

        if (onHold) {
            this.$el.find('#status-msg').text(text).show();
        } else {
            this.$el.find('#status-msg').text(text).show().delay(2000).fadeOut();
        }
    },

    setThisMonthPeriod: function () {
        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date(y, m + 1, 0);

        this.$el.find('#ea-filter-from').datepicker('setDate', firstDay);
        this.$el.find('#ea-filter-to').datepicker('setDate', lastDay);
    },

    setThisWeekPeriod: function () {
        this.$el.find('#ea-filter-from').datepicker('setDate', this.getMonday(new Date()));
        this.$el.find('#ea-filter-to').datepicker('setDate', this.getSunday(new Date()));
    },

    setThisDayPeriod: function () {
        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        this.$el.find('#ea-filter-from').datepicker('setDate', new Date());
        this.$el.find('#ea-filter-to').datepicker('setDate', tomorrow);
    }
});