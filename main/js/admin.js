(function($) {
    
    var EA = {};

    //?...

    var files = [
        "models/location.js",
        "models/service.js",
        "models/worker.js",
        "models/connection.js",
        "models/setting.js",
        "models/field.js",
        "models/error.js",
        "collections/fields.js",
        "collections/locations.js",
        "collections/services.js",
        "collections/workers.js",
        "collections/connections.js",
        "collections/settings.js",
        "collections/errors.js",
        "views/admin/connection.js",
        "views/admin/connection.bulk.js",
        "views/admin/connections.js",
        "views/admin/custumize.js",
        "views/admin/tools.js",
        "views/admin/main.js"
    ];

    for (var i = 0; i < files.length; i++) {
        include(files[i]);
    }

    //?.

    var mainView = new EA.MainView();

	//? include("admin-router.js")

}(jQuery));