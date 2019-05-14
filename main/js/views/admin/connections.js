// Main tamplate
EA.ConnectionsView = Backbone.View.extend({

    template : _.template( jQuery("#ea-tpl-connections-table").html() ),

    rowsView : null,

    events : {
        "click .add-new" : "addNew",
        "click .add-new-bulk": "addBulk",
        "click .refresh-list" : "refreshList"
    },

    locations : null,
    services : null,
    workers : null,

    initialize: function () {
        // bug fix for missing regional settings
        // jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ea_settings.datepicker] );
        if (typeof jQuery.timepicker.regional[ea_settings.datepicker] !== 'undefined') {
            jQuery.timepicker.setDefaults( jQuery.timepicker.regional[ea_settings.datepicker] );
        }

        // Empty array of connections
        this.collection = new EA.Connections();
        
        if(typeof eaData !== 'undefined'){
            // In page cache
            this.locations  = new EA.Locations(eaData.Locations);
            this.services   = new EA.Services(eaData.Services);
            this.workers    = new EA.Workers(eaData.Workers);
        } else {
            // Get from server
            this.locations  = new EA.Locations();
            this.services   = new EA.Services();
            this.workers    = new EA.Workers();

            this.locations.fetch();
            this.services.fetch();
            this.workers.fetch();
        }

        // Table draw
        this.render();

        // Bind the reset event 
        this.collection.bind("reset", this.render, this);

        this.showMessage('Table loading...', true);
        // Get data from server
        this.collection.fetch( {reset:true}, {
            success : function () {
                this.showMessage('Loading table...');
            }
        } );
    },

    render: function () {

        this.$el.empty(); // clear the element to make sure you don't double your contact view
        
        this.$el.html( this.template );

        var self = this; // so you can use this inside the each function

        this.collection.each(function(connection) { // iterate through the collection
            var connectionView = new EA.ConnectionView({
                model: connection,
                parent: self
            });

            connectionView.setData(
                self.locations,
                self.services,
                self.workers
            );

            connectionView.render();

            self.$el.find("#ea-connections").append(connectionView.$el);
        });

        return this;
    },

    addNew: function(e) {
        e.preventDefault();

        var connection = new EA.Connection();
        var self = this;

        this.collection.add(connection, {at: 0});

        var connectionView = new EA.ConnectionView({
            model: connection,
            parent: self
        });

        connectionView.setData(
            this.locations,
            this.services,
            this.workers
        );

        this.$el.find("#ea-connections").prepend(connectionView.$el);

        connectionView.edit();
    },

    getMax: function() {
        if (this.collection.length === 0) {
            return 0;
        }

        var maxModel = _.max(this.collection.toJSON(), function(model) {

            return model.id;
        });

        return parseInt(maxModel.id);
    },

    addBulk: function(e) {
        e.preventDefault();

        var self = this;

        var connection = new EA.Connection();

        var connectionBulkView = new EA.ConnectionBulkView({
            model: connection,
            parent: self
        });

        connectionBulkView.setData(
            this.locations,
            this.services,
            this.workers
        );

        this.$el.find("#bulk-connections-builder-content").html(connectionBulkView.$el);

        connectionBulkView.edit();

        tb_show('Bulk Connections builder', '#TB_inline?height=420&width=900&inlineId=bulk-connections-builder');
    },

    refreshList: function(e) {
        e.preventDefault();

        this.showMessage('Loading table...', true);

        this.collection.fetch( {reset:true}, {
            error: function(response){
                that.showMessage('');
                alert('Error, try refresh again.');
            },
            success: function(){
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
    }
});

/**
 * Safe validation
 *
 * @param collection
 * @param id
 * @param returnedParamName
 * @returns {*}
 */
function findWhereSafe(collection, id, returnedParamName) {
    var result = _.findWhere(collection, {id:id});

    if (_.isUndefined(result)) {
        return '-- DELETED --';
    }

    return result[returnedParamName];
}

_.mixin({
    findWhereSafe:findWhereSafe
});