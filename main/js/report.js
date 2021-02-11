(function($) {

    var EA = {};

    //?...

    var files = [
        "backbone.sync.fix.js",
        "models/setting.js",
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