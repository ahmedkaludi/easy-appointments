/**
 * Services collection
 */
EA.Services = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_services' + '&_wpnonce=' + (window?.wpApiSettings?.nonce ?? ''),
    model: EA.Service,
    parse: function(response) {
        // console.log(response);
        return response;
    },
    cacheData: function() {
        if(typeof eaData !== 'undefined') {
            eaData.Services = this.toJSON();
        }
    }
});