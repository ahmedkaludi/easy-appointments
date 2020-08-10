// Main tamplate
EA.ToolsView = Backbone.View.extend({

    template : _.template( jQuery("#ea-tpl-tools").html() ),
    template_error_log : _.template( jQuery("#ea-tpl-tools-log").text() ),

    events: {
        "click #test-wp-mail" : "testWPMail",
        "click #test-mail" : "testMail",
        "click #ea-clear-log" : "clearLog"
    },

    initialize: function () {

        this.render();

        this.errors = new EA.Errors();

        this.errors.bind("reset", this.renderLog, this);

        this.errors.fetch({reset:true});
    },

    render: function () {
        var obj = this;
        this.$el.empty(); // clear the element to make sure you don't double your contact view

        var content = this.template( {} );

        this.$el.html( content );

        return this;
    },

    testWPMail: function() {
        var data = {
            action: 'ea_test_wp_mail',
            address: jQuery('#test-email-address').val(),
            native: 0
        };

        jQuery.post(ajaxurl, data, function(response) {
            alert(response);
        });
    },

    testMail: function() {
        var data = {
            action: 'ea_test_wp_mail',
            address: jQuery('#test-email-address').val(),
            native: 1
        };

        jQuery.post(ajaxurl, data, function(response) {
            alert(response);
        });
    },

    /**
     * Renders error log textarea
     */
    renderLog: function () {
        var obj = this, content = '';

        this.errors.each(function(model, index) {
            var o = model.toJSON();

            content += obj.template_error_log({item : o});
        });

        this.$el.find('#ea-error-log').val(content);
    },

    destroy_view: function() {

        // COMPLETELY UNBIND THE VIEW
        this.undelegateEvents();

        this.$el.removeData().unbind();

        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },

    /**
     * Clear email log records from DB
     */
    clearLog: function () {
        var endpoint = ea_settings.rest_url + 'easy-appointments/v1/mail_log?_wpnonce=' + wpApiSettings.nonce;
        jQuery.ajax({
            url: endpoint,
            type: 'DELETE',
            success: function(result) {
                alert(result);

                this.$el.find('#ea-error-log').val('');
            }
        });
    }
});