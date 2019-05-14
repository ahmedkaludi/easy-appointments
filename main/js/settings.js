(function($) {

    var EA = {};

    //?...

    var files = [
        "formater.js",
        "backbone.sync.fix.js",
        "models/location.js",
        "models/service.js",
        "models/worker.js",
        "models/connection.js",
        "models/appointment.js",
        "collections/locations.js",
        "collections/services.js",
        "collections/workers.js",
        "collections/connections.js",
        "collections/appointments.js",
        "views/appointments/main.js",
        "views/appointments/appointment.js"
    ];
    
    for (var i = 0; i < files.length; i++) {
        include(files[i]);
    }

    //?.

    var mainView = new EA.MainView();

}(jQuery));