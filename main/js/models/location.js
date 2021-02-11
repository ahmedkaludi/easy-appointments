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

    url: function() { return ajaxurl+'?action=ea_location&id=' + encodeURIComponent(this.id) },

    toJSON : function() {
        var attrs = _.clone( this.attributes );
        return attrs;
    }
});