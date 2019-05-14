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
        return ajaxurl+'?action=ea_worker&id=' + this.id;
    },
    toJSON : function() {
        var attrs = _.clone( this.attributes );
        return attrs;
    }
});