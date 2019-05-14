/**
 * Single field
 */
EA.Field = Backbone.Model.extend({

	defaults : {
		type: 'INPUT',
		slug: '',
		label: '',
		default_value: '',
		validation: false,
		mixed: '',
		visible: true,
		required: false,
		position: 10,
	},
	url: function() { return ajaxurl+'?action=ea_field&id=' + encodeURIComponent(this.id); },
	toJSON: function() {
		var attrs = _.clone( this.attributes );
		//console.log(attrs);
		return attrs;
	}
});