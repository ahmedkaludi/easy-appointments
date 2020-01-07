/**
 * Single connection
 */
EA.Connection = Backbone.Model.extend({
    defaults : {
        group_id : null,
        location : null,
        service : null,
        worker : null,
        slot_count: 1,
        day_of_week : [],
        time_from : null,
        time_to : null,
        day_from : '2018-01-01',
        day_to : '2020-01-01',
        is_working : 0
    },

    url: function() { return ajaxurl+'?action=ea_connection&id=' + encodeURIComponent(this.id) },

    toJSON: function() {
        var attrs = _.clone( this.attributes );

        return attrs;
    },

    parse: function(data, options) {

        if(typeof data.day_of_week !== "undefined" && data.day_of_week != null) {
            data.day_of_week = data.day_of_week.split(',');
        } else {
            // console.log(this.get('day_of_week').split(','));
            this.set('day_of_week', this.get('day_of_week'));
        }

        if(typeof data.time_from !== "undefined" && typeof data.time_to !== "undefined") {

            if(data.time_from.length === 8) {
                data.time_from = data.time_from.substring(0, 5);
            }

            if(data.time_to.length === 8) {
                    data.time_to = data.time_to.substring(0, 5);
            }
        }

        return data;
    },

    save: function(attrs, options) {
        options || (options = {});
        attrs || (attrs = _.clone(this.attributes));

        attrs.day_of_week = attrs.day_of_week.join(',');

        return Backbone.Model.prototype.save.call(this, attrs, options);
    }
});