(function($) {

  function init(template) {
    template.find('.ea-wizard-calendar').datepicker();

    var templateLocation = _.template($('#ea-wizard-template-location').html());
    var templateService = _.template($('#ea-wizard-template-service').html());
    var templateWorker = _.template($('#ea-wizard-template-worker').html());

    eaData.Locations.forEach(function (location) {
      template.find('.ea-step-location-content').append(templateLocation({location: location}));
    });

    eaData.Services.forEach(function (service) {
      template.find('.ea-step-service').append(templateService({service: service}));
    });

    eaData.Workers.forEach(function (worker) {
      template.find('.ea-step-worker').append(templateWorker({worker: worker}));
    });
  }

  function toggleButtons(template) {
    const steps = template.find('.ea-wizard-step');
    const first = steps.first();
    const last = steps.last();

    if (first.hasClass('current')) {
      template.find('.ea-wizard-prev').hide();
      template.find('.ea-wizard-submit').hide();
      template.find('.ea-wizard-close').hide();
      template.find('.ea-wizard-next').show();
    } else if (last.hasClass('current')) {
      template.find('.ea-wizard-next').hide();
      template.find('.ea-wizard-prev').hide();
      template.find('.ea-wizard-submit').hide();
      template.find('.ea-wizard-close').show();
    } else if (last.prev().hasClass('current')) {
      template.find('.ea-wizard-close').hide();
      template.find('.ea-wizard-next').hide();
      template.find('.ea-wizard-prev').show();
      template.find('.ea-wizard-submit').show();
    } else {
      template.find('.ea-wizard-close').hide();
      template.find('.ea-wizard-submit').hide();
      template.find('.ea-wizard-prev').show();
      template.find('.ea-wizard-next').show();
    }
  }

  function showStep(template, direction) {
    template.find('.ea-wizard-progress-step').removeClass('done');

    if (direction === 'next') {
      template.find('.ea-wizard-step.current').removeClass('current').next().addClass('current');
      template.find('.ea-wizard-progress-step.current').removeClass('current').next().addClass('current').prevAll().addClass('done');
    } else {
      template.find('.ea-wizard-step.current').removeClass('current').prev().addClass('current');
      template.find('.ea-wizard-progress-step.current').removeClass('current').prev().addClass('current').prevAll().addClass('done');
    }

    toggleButtons(template);
  }

  function setChosen(me) {
    me.siblings().removeClass('chosen');
    me.addClass('chosen');
  }

  $('.ea-wizard-button').on('click', function() {
    var template = $($('#ea-wizard-template').html());

    init(template);

    template.find('.ea-wizard').on('click', function(e) {
      e.preventDefault();
      return false;
    });

    template.find('.ea-wizard-prev').on('click', function() {
      showStep(template, 'prev');
    });

    template.find('.ea-wizard-next').on('click', function() {
      showStep(template, 'next');
    });

    template.find('.ea-wizard-submit').on('click', function() {
      showStep(template, 'next');
    });

    template.find('.ea-wizard-close').on('click', function() {
      template.hide();
    });

    template.find('.ea-wizard-close-modal').on('click', function() {
      template.hide();
    });

    template.find('.choice').on('click', function() {
      setChosen($(this));
      // $(this).siblings().removeClass('chosen');
      // $(this).addClass('chosen');
    });
    
    template.show().appendTo('body');
  });
}(jQuery));