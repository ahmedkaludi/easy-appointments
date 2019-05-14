/**
 * Connections collection
 */
EA.Errors = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_errors',
    model: EA.Error
});
