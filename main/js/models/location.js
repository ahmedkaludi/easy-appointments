/**
 * Single location
 */
EA.Location = Backbone.Model.extend({
    defaults : {
        name:"",
        address: "",
        location: "",
        cord: null
    },

    url: function() {
        const nonce = window?.wpApiSettings?.nonce ?? '';
        return ajaxurl+'?action=ea_location&id=' + encodeURIComponent(this.id) + '&_wpnonce=' + nonce;
    },

    toJSON : function() {
        var attrs = _.clone( this.attributes );
        return attrs;
    }
});