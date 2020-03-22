(function($) {

    $('.ea-wizard-button').on('click', function() {
        var template = $($('#ea-wizard-template').html());

        template.find('.ea-wizard').on('click', function(e) {
           e.preventDefault();
           return false;
        });

        template.find('.ea-wizard-next').on('click', function() {
            template.find('.ea-wizard-step.current').removeClass('current').next().addClass('current');

            if (template.find('.ea-wizard-step').last().hasClass('current')) {
                $(this).hide();
            }

            template.find('.ea-wizard-prev').show();
        });

        template.find('.ea-wizard-prev').on('click', function() {
            template.find('.ea-wizard-step.current').removeClass('current').prev().addClass('current');

            if (template.find('.ea-wizard-step').first().hasClass('current')) {
                $(this).hide();
            }

            template.find('.ea-wizard-next').show();
        });

        template.show().appendTo('body').on('click', function() {
            $(this).hide();
        });
    });
}(jQuery));