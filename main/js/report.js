(function($) {

    var EA = {};

    //?...

    var files = [
        "backbone.sync.fix.js",
        "models/location.js",
        "models/service.js",
        "models/worker.js",
        "models/connection.js",
        "models/setting.js",
        "collections/locations.js",
        "collections/services.js",
        "collections/workers.js",
        "collections/connections.js",
        "collections/settings.js",
        "views/reports/report.js",
        "views/reports/overview.js",
        "views/reports/excel.js",
    ];

    for (var i = 0; i < files.length; i++) {
        include(files[i]);
    }

    //?.

    var mainView = new EA.ReportView();

}(jQuery));