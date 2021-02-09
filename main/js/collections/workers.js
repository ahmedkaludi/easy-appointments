/**
 * Workers collection
 */
EA.Workers = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_workers',
    model: EA.Worker,
    cacheData: function() {
        if(typeof eaData !== 'undefined') {
            eaData.Workers = this.toJSON();
        }
    }
});