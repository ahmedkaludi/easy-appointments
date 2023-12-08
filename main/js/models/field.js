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
	url: function() {
		const nonce = window?.wpApiSettings?.nonce ?? '';
		return ajaxurl+'?action=ea_field&id=' + encodeURIComponent(this.id) + '&_wpnonce=' + nonce;
	},
	toJSON: function() {
		var attrs = _.clone( this.attributes );
		//console.log(attrs);
		return attrs;
	}
});