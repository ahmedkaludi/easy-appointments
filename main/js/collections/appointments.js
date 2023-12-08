/**
 * Appointments collection
 */
EA.Appointments = Backbone.Collection.extend({
    url : ajaxurl + '?action=ea_appointments' + '&_wpnonce=' + (window?.wpApiSettings?.nonce ?? ''),
    model: EA.Appointment
});