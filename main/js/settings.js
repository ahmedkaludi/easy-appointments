(function($) {

    var EA = {};

    //?...

    var files = [
        "formater.js",
        "backbone.sync.fix.js",
        "models/appointment.js",
        "models/location.js",
        "models/service.js",
        "models/worker.js",
        "collections/appointments.js",
        "collections/locations.js",
        "collections/services.js",
        "collections/workers.js",
        "views/appointments/main.js",
        "views/appointments/appointment.js"
    ];
    
    for (var i = 0; i < files.length; i++) {
        include(files[i]);
    }

    //?.

    var mainView = new EA.MainView();

}(jQuery));