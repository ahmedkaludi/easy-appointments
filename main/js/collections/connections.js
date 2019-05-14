/**
 * Connections collection
 */
EA.Connections = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_connections',
    model: EA.Connection
});