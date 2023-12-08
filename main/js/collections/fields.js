/**
 * Connections collection
 */
EA.Fields = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_fields' + '&_wpnonce=' + (window?.wpApiSettings?.nonce ?? ''),
    model: EA.Field
});