// Main tamplate
EA.ServicesView = Backbone.View.extend({
    //el: jQuery("#wpbody-content"),

    template : _.template( jQuery("#ea-tpl-services-table").html() ),

    rowsView : null,

    events : {
        "click .add-new" : "addNew",
        "click .refresh-list" : "refreshList",
        "change #sort-services-by": "sortChange",
        "change #order-services-by": "sortChange"
    },

    initialize: function () {
        // Get pre chache data
        if(typeof eaData !== 'undefined'){
            this.collection = new EA.Services(eaData.Services);
        } else {
            this.collection = new EA.Services();    
        }

        // Table draw
        this.render();

        // Bind the reset event
        this.collection.bind("reset", this.render, this);

        // if there is no data in cache
        if( this.collection.length == 0 ) {
            // Get data from server
            this.collection.fetch( {reset:true} );
        }
    },

    render: function () {

        this.$el.empty(); // clear the element to make sure you don't double your contact view
        
        this.$el.html( this.template );

        var self = this; // so you can use this inside the each function

        this.collection.each(function(service) { // iterate through the collection
            var serviceView = new EA.ServiceView({
                model: service,
                parent: self
            });

            self.$el.find("#ea-services").append(serviceView.$el);
        });

        if(typeof eaData !== 'undefined') {
            eaData.Services = this.collection.toJSON();
        }

        this.$el.find('#sort-services-by').val(ea_settings['sort.services-by']);
        this.$el.find('#order-services-by').val(ea_settings['order.services-by']);

        return this;
    },

    addNew: function(e) {
        e.preventDefault();

        var service = new EA.Service();
        var self = this;

        this.collection.add(service, {at: 0});

        var serviceView = new EA.ServiceView({
            model: service,
            parent: self
        });

        this.$el.find("#ea-services").prepend(serviceView.$el);

        serviceView.edit();
    },

    refreshList: function(e) {
        e.preventDefault();

        var that = this;

        this.showMessage('Loading table...', true);

        this.collection.fetch( {reset:true}, {
            error: function(response) {
                that.showMessage('');
                alert('Error, try refresh again.');
            },
            success: function() {
                that.showMessage('');
            }
        });
    },

    destroy_view: function() {
        // COMPLETELY UNBIND THE VIEW
        this.undelegateEvents();

        this.$el.removeData().unbind(); 

        // Remove view from DOM
        this.remove();  
        Backbone.View.prototype.remove.call(this);
    },

    showMessage: function(text, hold) {
        var onHold = hold || false;

        if(onHold) {
            this.$el.find('#status-msg').text(text).show();
        } else {
            this.$el.find('#status-msg').text(text).show().delay(2000).fadeOut();
        }
    },

    sortChange: function (event) {
        var plugin = this;

        var column = this.$el.find('#sort-services-by').val();
        var type = this.$el.find('#order-services-by').val();

        var columnSettings = new EA.Setting();
        columnSettings.set('ea_key', 'sort.services-by');
        columnSettings.set('ea_value', column);
        columnSettings.set('type', 'default');
        var d1 = columnSettings.save();

        var orderSettings = new EA.Setting();
        orderSettings.set('ea_key', 'order.services-by');
        orderSettings.set('ea_value', type);
        orderSettings.set('type', 'default');
        var d2 = orderSettings.save();

        jQuery.when(d1, d2).done(function () {
            ea_settings['sort.services-by'] = column;
            ea_settings['order.services-by'] = type;

            plugin.refreshList(jQuery.Event( "click" ));
        });
    }
});