(function($) {
    
    var EA = {};

    //?...

    var files = [
        "models/setting.js",
        "models/field.js",
        "collections/fields.js",
        "collections/settings.js",
        "views/admin/custumize.js",
        "views/admin/main.js"
    ];

    for (var i = 0; i < files.length; i++) {
        include(files[i]);
    }

    //?.

    var mainView = new EA.MainView();

    var custumize = new EA.CustumizeView({
        el: '#tab-content'
    });

}(jQuery));