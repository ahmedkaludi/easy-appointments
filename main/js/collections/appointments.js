/**
 * Appointments collection
 */
EA.Appointments = Backbone.Collection.extend({
    url : ajaxurl+'?action=ea_appointments',
    model: EA.Appointment
});