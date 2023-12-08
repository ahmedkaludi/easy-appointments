/**
 * Settings collection
 */
EA.Settings = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_settings' + '&_wpnonce=' + (window?.wpApiSettings?.nonce ?? ''),
    model: EA.Setting
});

/**
 * Wrapper around settings data
 */
EA.SettingsWrapper = Backbone.Model.extend({
	url : ajaxurl+'?action=ea_settings' + '&_wpnonce=' + (window?.wpApiSettings?.nonce ?? '')
	/*toJSON : function() {
		return this.model.toJSON();
	}*/
});