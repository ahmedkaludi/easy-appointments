/**
 * Overvire report view
 */
EA.ExcelReportView = Backbone.View.extend({

	template : _.template( jQuery("#ea-report-excel").html() ),

	events : {
		//  'click .eadownloadcsv': 'download',
        "click #ea-export-customize-columns-toggle" : "toggleColumnSettings",
        "click #ea-export-save-custom-columns" : "saveCustomColumns"
	},

	initialize: function () {
		jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ea_settings.datepicker] );

		// this.render();
	},

	render: function() {
		var view = this;

		this.$el.empty();

		this.$el.html( this.template({export_link: ajaxurl}));

		this.$el.find('.ea-datepicker').datepicker({
			dateFormat: 'yy-mm-dd'
		});

		return this;
	},

	download: function () {

		var fields = [];
		fields.push({ 'name' : 'action', 'value': 'ea_export' });

		jQuery.get(ajaxurl, fields, function(result) {});
	},

    /**
     * Toggle settings
     */
    toggleColumnSettings: function() {
        jQuery('#ea-export-customize-columns').toggle();
    },

    /**
     *
     */
    saveCustomColumns: function() {

        var data = {
            fields: this.$el.find('#ea-export-custom-columns').val(),
            action: 'ea_save_custom_columns'
        };

        jQuery.post(ajaxurl, data, function(result) {
            alert('Settings saved');
        });
    }

});