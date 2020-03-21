(function($) {

    $('.ea-wizard-button').on('click', function() {
        var template = $('#ea-wizard-template').html();

        $(template).show().appendTo('body').on('click', function() {
            $(this).hide();
        });
    });
}(jQuery));