/**
 * Workers collection
 */
EA.Workers = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_workers' + '&_wpnonce=' + (window?.wpApiSettings?.nonce ?? ''),
    model: EA.Worker,
    cacheData: function() {
        if(typeof eaData !== 'undefined') {
            eaData.Workers = this.toJSON();
        }
    }
});