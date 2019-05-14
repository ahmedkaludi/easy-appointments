/**
 * Locations collection
 */
EA.Locations = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_locations',
    model: EA.Location,
    cacheData: function() {
        if(typeof eaData !== 'undefined') {
            eaData.Locations = this.toJSON();
        }
    }
});