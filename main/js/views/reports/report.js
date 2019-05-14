/**
 * Main Report View
 * Renders Report Admin page
 *
 **/
EA.ReportView = Backbone.View.extend({
	el : jQuery('#wpbody-content'),

	template : _.template( jQuery("#ea-report-main").html() ),

	events : {
        "click .report"  : "reportSelected"
	},

	initialize: function () {
		this.render();

	},

	render: function () {
		this.$el.empty(); 

		this.$el.html( this.template());

		return this;
	},

	reportSelected: function(elem) {
		var report = jQuery(elem.currentTarget).data('report');

		var menu = jQuery(elem.currentTarget).closest('ul');
		menu.find('.tab-selected').removeClass('tab-selected');

		jQuery(elem.currentTarget).addClass('tab-selected');

		var currentView = null;

		switch (report) {
			case 'overview' :
				currentView = new EA.OverviewReportView();
				break;
			case 'excel' :
				currentView = new EA.ExcelReportView();
				break;
		}

		var output = currentView.render();

		this.$el.find('#report-content').html(output.$el);
	},
});