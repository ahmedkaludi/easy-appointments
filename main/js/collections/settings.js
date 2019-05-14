/**
 * Settings collection
 */
EA.Settings = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_settings',
    model: EA.Setting
});

/**
 * Wrapper around settings data
 */
EA.SettingsWrapper = Backbone.Model.extend({
	url : ajaxurl+'?action=ea_settings',
	/*toJSON : function() {
		return this.model.toJSON();
	}*/
});