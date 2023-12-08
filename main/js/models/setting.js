/**
 * Service model
 */
EA.Setting = Backbone.Model.extend({
    defaults : {
        ea_key:"",
        ea_value : "",
        type: ""
    },
    url : function() {
        const nonce = window?.wpApiSettings?.nonce ?? '';
        return ajaxurl+'?action=ea_setting&id=' + this.id + '&_wpnonce=' + nonce;
    },
    toJSON : function() {
        var attrs = _.clone( this.attributes );
        return attrs;
    },
    parse: function(data, options) {
        // console.log(data);
        return data;
    }
});