(function($) {

    var EA = {};

    //?...

    var files = [
        "formater.js",
        "backbone.sync.fix.js",
        "models/appointment.js",
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