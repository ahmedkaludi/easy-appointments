/**
 * Single Appointment
 */
EA.Appointment = Backbone.Model.extend({
    defaults : {
        location    : null,
        service     : null,
        worker      : null,
        // name        : '',
        // email       : '',
        // phone       : '',
        date        : null,
        start       : null,
        end         : null,
        end_date    : null,
        description : null,
        status      : null,
        user        : null,
        price       : 0
    },
    
    url: function() { return ajaxurl+'?action=ea_appointment&id=' + encodeURIComponent(this.id) },
    
    toJSON : function() {
        var attrs = _.clone( this.attributes );
        return attrs;
    },

    parse: function(data, options) {

        if(typeof data.start !== "undefined" && data.start != null && data.start.length === 8) {
            data.start = data.start.substring(0, 5);
        }

        if(typeof data.created !== "undefined" && data.created.length === 19) {
            data.created = data.created.substring(0, 16);
        }

        return data;
    }
});