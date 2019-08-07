/**
 * Main Report View
 * Renders Report Admin page
 *
 **/
EA.ReportView = Backbone.View.extend({
    el: jQuery('#wpbody-content'),

    template: _.template(jQuery("#ea-report-main").html()),

    events: {
        "click .report-card": "reportSelected",
        "click .go-back": "goBackAction"
    },

    initialize: function () {
        this.render();

    },

    render: function () {
        this.$el.empty();

        this.$el.html(this.template());

        return this;
    },

    reportSelected: function (elem) {
        var report = jQuery(elem.currentTarget).data('report');

        var currentView = null;

        switch (report) {
            case 'overview' :
                currentView = new EA.OverviewReportView();
                break;
            case 'excel' :
                currentView = new EA.ExcelReportView();
                break;
        }

        this.$el.find('.report-items').hide();
        this.$el.find('.back-section').show();

        var output = currentView.render();

        this.$el.find('#report-content').html(output.$el);
    },

    goBackAction: function () {
        this.$el.find('.back-section').hide();
        this.$el.find('.report-items').show();

        this.$el.find('#report-content').empty();
    }
});