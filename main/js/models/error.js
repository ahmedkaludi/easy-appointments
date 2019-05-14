/**
 * Single error
 */
EA.Error = Backbone.Model.extend({

    defaults : {
        error_type: '',
        errors: '',
        errors_data: '',
    },
    url: function() { return ajaxurl+'?action=ea_error&id=' + encodeURIComponent(this.id); },
});