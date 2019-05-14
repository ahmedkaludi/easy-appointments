/**
 * Bulk component
 */
EA.ConnectionBulkView = Backbone.View.extend({
    tagName:  "div",

    // edit template
    template_edit : _.template( jQuery("#ea-tpl-connection-bulk").html() ),
    template_bulk_connections: _.template( jQuery("#ea-tpl-single-bulk-connection").html() ),

    locations : null,
    services : null,
    workers : null,

    connections: [],

    events: {
        'click #bulk-next': 'bulkNext',
        'click #bulk-save': 'save',
        'click #bulk-connection-remove': 'removeConnection'
    },

    initialize: function (options) {
        this.parent = options.parent;
    },

    render: function () {
        var self = this;

        //after save split days of week
        if(!jQuery.isArray(this.model.get('day_of_week'))) {
            this.model.set('day_of_week', this.model.get('day_of_week').split(','));
        }

        var renderedContent = this.template( {
            row       : this.model.toJSON(),
            locations : self.locations.toJSON(),
            services  : self.services.toJSON(),
            workers   : self.workers.toJSON()
        } );

        jQuery(this.el).html( renderedContent );

        return this;
    },

    /**
     * Calculate number of combinations
     *
     * @returns {number}
     */
    connectionsCount: function() {
        var values = ['location', 'service', 'worker'];

        var locations = this.$el.find('[data-prop="location"]').val();
        var services = this.$el.find('[data-prop="service"]').val();
        var workers = this.$el.find('[data-prop="worker"]').val();

        if (locations === null || services === null || workers === null) {
            return 0;
        }

        return locations.length * services.length * workers.length;
    },

    /**
     *
     */
    processConnections: function() {
        var locations = this.$el.find('[data-prop="location"]').val();
        var services = this.$el.find('[data-prop="service"]').val();
        var workers = this.$el.find('[data-prop="worker"]').val();

        var day_of_week = this.$el.find('[data-prop="day_of_week"]').val();

        var time_from = this.$el.find('[data-prop="time_from"]').val();
        var time_to = this.$el.find('[data-prop="time_to"]').val();

        var day_from = this.$el.find('[data-prop="day_from"]').val();
        var day_to = this.$el.find('[data-prop="day_to"]').val();

        var is_working = this.$el.find('[data-prop="is_working"]').val();

        var nextId = this.parent.getMax() + 1;

        var connections = [];

        jQuery.each(locations, function(i, location) {
            jQuery.each(services, function(j, service) {
                jQuery.each(workers, function(k, worker) {
                    var model = new EA.Connection({
                        group_id : nextId,
                        location : location,
                        service : service,
                        worker : worker,
                        day_of_week : day_of_week,
                        time_from : time_from,
                        time_to : time_to,
                        day_from : day_from,
                        day_to : day_to,
                        is_working : is_working
                    });

                    connections.push(model);
                });
            });
        });

        return connections;
    },

    removeButtonClick: function(e) {
        var element = jQuery(e.target);

        var connection = element.data('model');

        this.removeConnection(connection);
        element.closest('li').remove();
    },

    removeConnection: function(connection) {
        var self = this;
        jQuery.each(this.connections, function(i, current) {
            if (current == connection) {
                self.connections.splice(i, 1);
            }
        });

        this.updateConnectionsCount(this.connections.length);
    },

    /**
     * Update html element
     */
    updateConnectionsCount: function(number) {
        this.$el.find('#bulk-connection-count').text(number);
    },

    /**
     * Edit state
     */
    edit: function() {

        if(this.edit_mode) {
            return;
        }

        var plugin = this;

        // Edit class
        this.$el.addClass('ea-editing');

        this.template = this.template_edit;

        this.render();

        this.$el.find(".day-from").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1
        });

        this.$el.find(".time-from").timepicker();

        this.$el.find(".day-to").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1
        });

        this.$el.find(".time-to").timepicker();

        this.$el.find('select, input').first().focus();

        this.$el.find('.chosen-select').chosen({
            placeholder_text_multiple: ' ',
            width: '100%'
        }).change(function() {
            var num = plugin.connectionsCount();
            plugin.updateConnectionsCount(num);
        });

    },

    bulkNext: function(e) {
        e.preventDefault();

        var self = this;

        if (!this.validateConnection()) {
            return;
        }

        this.connections = this.processConnections();
        var list = this.$el.find('#bulk-connections');

        jQuery.each(this.connections, function(i, model) {
            var renderedContent = self.template_bulk_connections( {
                row       : model.toJSON(),
                locations : self.locations.toJSON(),
                services  : self.services.toJSON(),
                workers   : self.workers.toJSON()
            });

            var content = jQuery(renderedContent);

            list.append(content);

            content
                .find('.bulk-connection-remove')
                .on('click', jQuery.proxy(self.removeButtonClick, self))
                .data('model', model);

        });

        this.$el.find('.step-1').hide();
        this.$el.find('#bulk-next').hide();
        this.$el.find('.step-2').show();
        this.$el.find('#bulk-save').prop('disabled', false);
    },

    save: function(e) {
        jQuery(e.target).prop('disabled', true);

        var self = this;

        if (this.connections.length == 0) {
            tb_remove();
            this.parent.refreshList(e);
        }

        var connection = this.connections.shift();

        if (typeof connection == 'undefined') {
            return;
        }

        connection.save().then(function() {
            self.removeConnection(connection);
            self.$el.find('#bulk-connections').children().first().remove();
            self.updateConnectionsCount(self.connections.length);
            self.save(e);
        });
    },

    validateConnection: function(connection) {
        var $el = this.$el;
        var result = true;
        var values = ['location', 'service', 'worker', 'day_of_week', 'time_from', 'time_to'];

        jQuery.each(values, function(index, element) {
            var elementField = $el.find('[data-prop="' + element + '"]');

            if (elementField.hasClass('chosen-select')) {
                var selection = elementField.val();

                if (selection !== null && selection.length > 0) {
                    return;
                }

                elementField = elementField.next().children();

            } else {
                if (elementField.val() != '' && elementField.val() != null) {
                    return;
                }
            }

            elementField.css({border: '1px solid #ff0000'}).animate({
                backgroundColor: 'white'
            }, 2000, function() {
                elementField.css({border: '1px solid #aaa'});
            });

            result = false;
        });


        // valide select time of day
        if (result) {
            var time_from = parseInt($el.find('[data-prop="time_from"]').val().replace(':', ''));
            var time_to   = parseInt($el.find('[data-prop="time_to"]').val().replace(':', ''));

            if (time_from > time_to) {
                var fromField = $el.find('[data-prop="time_from"]');
                var toField   = $el.find('[data-prop="time_to"]');

                fromField.css({border: '1px solid #ff0000'}).animate({
                    backgroundColor: 'white'
                }, 2000, function() {
                    fromField.css({border: '1px solid #aaa'});
                });

                toField.css({border: '1px solid #ff0000'}).animate({
                    backgroundColor: 'white'
                }, 2000, function() {
                    toField.css({border: '1px solid #aaa'});
                });

                result = false;
            }
        }

        return result;
    },

    setData: function(locations, services, workers) {
        this.locations = locations;
        this.services  = services;
        this.workers   = workers;
    }
});