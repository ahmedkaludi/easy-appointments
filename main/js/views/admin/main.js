/**
 * Main Admin View
 * Renders Admin tab panel
 *
 **/
EA.MainView = Backbone.View.extend({
    el : jQuery('#wpbody-content'),

    template : _.template( jQuery("#ea-settings-main").html() ),

    events : {
        "click #tab-header li a" : "select"
    },

    initialize: function () {

        this.render();

    },

    render: function () {

        this.$el.empty(); 
        
        this.$el.html( this.template );

        return this;
    },

    addContainer: function () {

        if( this.$el.find('#tab-content').length > 0 ) {
            return;
        }

        this.$el.children('.wrap').append(
            jQuery( document.createElement('div') )
                .attr( 'id', 'tab-content' )
        );
    },

    select: function(e) {
        // console.log(e);
        var element = jQuery(e.target);

        this.$el.find('#tab-header li').removeClass('tab-selected');

        element.parents('li:first').addClass('tab-selected');
    }
});