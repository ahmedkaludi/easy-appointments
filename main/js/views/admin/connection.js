/**
 * 
 */
EA.ConnectionView = Backbone.View.extend({
    tagName:  "tr",

    // show template
    template_show : _.template( jQuery("#ea-tpl-connection-row").html() ),

    // edit template
    template_edit : _.template( jQuery("#ea-tpl-connection-row-edit").html() ),

    template : null,

    locations : null,
    services : null,
    workers : null,

    /**
     * Is edit mode active
     */
    edit_mode: false,

    events: {
        "click .btn-edit"   : "edit",
        "dblclick"          : "edit",
        "click .btn-del"    : "removeItem",
        "click .btn-save"   : "save",
        "click .btn-clone"  : "clone",
        "click .btn-cancel" : "cancel",
        "keydown input"     : "keydownEvent",
        "keydown select"    : "keydownEvent"
    },

    initialize: function (options) {
        this.template = this.template_show;
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
        
        this.$el.addClass('ea-row');

        return this;
    },

    edit: function() {

        if(this.edit_mode) {
            return;
        }

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

        this.edit_mode = true;
    },

    save: function() {
        var connection = this.model;
        var view = this;

        jQuery.each(this.$el.find('input, select'), function(index, elem){
            connection.set(jQuery(elem).data('prop'), jQuery(elem).val());
        });

        if (!this.validateConnection(connection)) {
            return;
        }

        this.parent.showMessage('Saving...');

        // Saves connection
        connection.save(null, {
            success: function(model, response) {
                view.render();
                view.parent.showMessage('Saved...');
            }
        });

        this.$el.removeClass('ea-editing');

        this.template = this.template_show;
        this.render();

        this.edit_mode = false;
    },

    validateConnection: function(connection) {
        var $el = this.$el;
        var result = true;
        var values = ['location', 'service', 'worker', 'day_of_week', 'time_from', 'time_to'];

        jQuery.each(values, function(index, element) {
            var current = connection.get(element);

            if ( current != '' && current != null ) {
                return;
            }

            var elementField = $el.find('[data-prop="' + element + '"]');

            elementField.css({border: '2px solid #ff0000'}).animate({
                backgroundColor: 'white'
            }, 2000, function() {
                elementField.css({border: '1px solid #ccc'});
            });

            result = false;
        });


        // valide select time of day
        if (result) {
            var time_from = parseInt(connection.get('time_from').replace(':', ''));
            var time_to   = parseInt(connection.get('time_to').replace(':', ''));

            if (time_from > time_to) {
                var fromField = $el.find('[data-prop="time_from"]');
                var toField   = $el.find('[data-prop="time_to"]');

                fromField.css({border: '2px solid #ff0000'}).animate({
                    backgroundColor: 'white'
                }, 2000, function() {
                    fromField.css({border: '1px solid #ccc'});
                });

                toField.css({border: '2px solid #ff0000'}).animate({
                    backgroundColor: 'white'
                }, 2000, function() {
                    toField.css({border: '1px solid #ccc'});
                });

                result = false;
            }
        }

        return result;
    },

    clone: function () {
        var connection = this.model;
        var clone = connection.clone();

        // clone.set('id', null);
        clone.unset('id');

        clone.save(null, {
            success: function(model, response) {

                connection.collection.fetch( {reset:true} );
            }
        });
    },

    cancel: function() {
        // If is new remove model/view
        if(this.model.isNew()) {
            this.parent.showMessage('New canceled');

            this.model.destroy();
            this.remove();
        } else {
            this.parent.showMessage('Edit canceled');

            this.$el.removeClass('ea-editing');

            this.template = this.template_show;
            this.render();
        }

        this.edit_mode = false;
    },

    // Delets model and view
    removeItem: function() {
        var view = this;

        if(confirm('Are you sure?')) {
            view.parent.showMessage('Deleting...');

            this.model.destroy({
                success: function(model, response) {
                    view.remove();
                    view.parent.showMessage('Done...');
                },
                error: function(model, response) {
                    view.parent.showMessage('Error...');
                }
            });
        }
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
    }
});