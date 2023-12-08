/**
 * Service model
 */
EA.Worker = Backbone.Model.extend({
    defaults : {
        name:"",
        description : "",
        email: "",
        phone: ""
    },
    url : function() {
        const nonce = window?.wpApiSettings?.nonce ?? '';
        return ajaxurl+'?action=ea_worker&id=' + this.id + '&_wpnonce=' + nonce;
    },
    toJSON : function() {
        var attrs = _.clone( this.attributes );
        return attrs;
    }
});