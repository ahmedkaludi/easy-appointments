/**
 * Connections collection
 */
EA.Fields = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_fields',
    model: EA.Field
});