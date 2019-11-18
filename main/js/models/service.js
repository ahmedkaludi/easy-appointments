/**
 * Service model
 */
EA.Service = Backbone.Model.extend({
    defaults : {
        name:"",
        duration: 60,
        slot_step: 60,
        block_before: 0,
        block_after: 0,
        price: 10
    },
    url : function() { 
        return ajaxurl+'?action=ea_service&id=' + this.id;
    },
    toJSON : function() {
        var attrs = _.clone( this.attributes );
        return attrs;
    }
});