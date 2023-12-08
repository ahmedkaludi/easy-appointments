/**
 * Overvire report view
 */
EA.OverviewReportView = Backbone.View.extend({

    template: _.template(jQuery("#ea-report-overview").html()),

    events: {
        'change select': 'selectChange',
        'click .refresh': 'selectChange'
    },

    initialize: function () {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ea_settings.datepicker]);

        // this.render();
    },

    render: function () {
        var view = this;

        this.$el.empty();

        this.$el.html(this.template({cache: eaData}));

        var options = {
            firstDay: 1,
            onChangeMonthYear: function (year, month, widget) {
                view.selectChange(month, year);
            },

            beforeShowDay: function (date) {
                var month = date.getMonth() + 1;
                var days = date.getDate();

                if (month < 10) {
                    month = '0' + month;
                }

                if (days < 10) {
                    days = '0' + days;
                }

                return [false, date.getFullYear() + '-' + month + '-' + days, ''];
            }
        };

        if (typeof jQuery.datepicker != 'undefined' &&
            typeof jQuery.datepicker.regional != 'undefined' &&
            typeof jQuery.datepicker.regional[ea_settings.datepicker] != 'undefined'
        ) {
            options.dayNamesMin = jQuery.datepicker.regional[ea_settings.datepicker].dayNames;
        }

        this.$el.find('.datepicker').datepicker(options);

        // do autoselect
        this.autoSelect();

        return this;
    },

    /**
     * Put default value into select box if there is only one option
     */
    autoSelect: function () {
        if (eaData.Locations.length === 1) {
            this.$el.find('#overview-location').val(eaData.Locations[0].id);
        }

        if (eaData.Services.length === 1) {
            this.$el.find('#overview-service').val(eaData.Services[0].id);
        }

        if (eaData.Workers.length === 1) {
            this.$el.find('#overview-worker').val(eaData.Workers[0].id);
        }

        // refresh data
        this.selectChange();
    },

    /**
     * Refresh data
     * by Month change or by Refresh button
     */
    selectChange: function (month, year) {
        var self = this;

        if (typeof month === 'undefined' || typeof year === 'undefined') {
            var currentDate = this.$el.find('.datepicker').datepicker('getDate');

            month = currentDate.getMonth() + 1;
            year = currentDate.getFullYear();
        }

        // check is all filled
        if (this.checkStatus()) {
            var selects = this.$el.find('select');

            var fields = selects.serializeArray();
            var nonce = window?.wpApiSettings?.nonce ?? '';

            fields.push({'name': 'action', 'value': 'ea_report'});
            fields.push({'name': 'report', 'value': 'overview'});
            fields.push({'name': 'month', 'value': month});
            fields.push({'name': 'year', 'value': year});
            fields.push({'name': '_wpnonce', 'value': nonce});

            jQuery.get(ajaxurl, fields, function (result) {
                self.refreshData(result);
            }, 'json');
        }
    },
    /**
     * Is everything selected
     * @return {boolean} Is ready for sending data
     */
    checkStatus: function () {
        var selects = this.$el.find('select');

        var isComplete = true;

        selects.each(function (index, element) {
            isComplete = isComplete && jQuery(element).val() !== '';
        });

        return isComplete;
    },

    refreshData: function (data) {
        var datepicker = this.$el.find('.datepicker');

        jQuery.each(data, function (key, slots) {
            var td = datepicker.find('.' + key);
            td.find('.single-item').remove();

            if (slots.length === 0) {
                td.addClass('empty-day');
                return;
            } else {
                td.removeClass('empty-day');
            }

            var itemElement;
            for (var i = 0; i < slots.length; i++) {

                itemElement = jQuery(document.createElement('div'))
                    .text(slots[i].show + ' - x ' + slots[i].count)
                    .addClass('single-item')
                    .addClass('free-items-' + slots[i].count)
                    .data('value', slots[i].value)
                    .appendTo(td);

                if (slots[i].count < 0) {
                    itemElement.addClass('error-booking');
                }
            }
        });
    }
});