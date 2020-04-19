<?php require_once EA_SRC_DIR . 'templates/inlinedata.sorted.tpl.php'; ?>
<!-- Template for Location Selection -->
<script id="ea-wizard-template-location" type="text/template">
    <div class="ea-wizard-location">
        <span><%- location.name %></span>
        <span><%- location.address %></span>
        <span><%- location.location %></span>
    </div>
</script>
<!-- Template for Service Selection -->
<script id="ea-wizard-template-service" type="text/template">
    <div class="ea-wizard-service">
        <span><%- service.name %></span>
        <span><%- service.duration %> minutes</span>
        <span>$<%- service.price %></span>
    </div>
</script>
<!-- Template for Worker Selection -->
<script id="ea-wizard-template-worker" type="text/template">
    <div class="ea-wizard-worker">
        <span><%- worker.name %></span>
        <span><%- worker.description %></span>
        <span><%- worker.phone %></span>
    </div>
</script>

<template id="ea-wizard-template">
  <div class="ea-wizard-overlay">
    <div class="ea-wizard">

      <div class="ea-wizard-header">
        <h3>Create an appointment</h3>
        <button type="button" class="ea-wizard-close-modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="ea-wizard-progress">
        <ul class="ea-wizard-progress-steps">
          <li class="ea-wizard-progress-step current" id="location"><span class="icon-location"></span>Location</li>
          <li class="ea-wizard-progress-step" id="worker">Worker</li>
          <li class="ea-wizard-progress-step" id="service">Service</li>
          <li class="ea-wizard-progress-step" id="calendar">Calendar</li>
          <li class="ea-wizard-progress-step" id="slots">Slots</li>
          <li class="ea-wizard-progress-step" id="personal">Personal Info</li>
          <li class="ea-wizard-progress-step" id="overview">Overview</li>
          <li class="ea-wizard-progress-step" id="thanks">Thanks</li>
        </ul>
      </div>

      <div class="ea-wizard-content">
        <!--<div class="ea-wizard-step current ea-loading">LOADING...BRE</div>-->
        <div class="ea-wizard-step ea-step-location current"></div>
        <div class="ea-wizard-step ea-step-worker"></div>
        <div class="ea-wizard-step ea-step-service"></div>
        <div class="ea-wizard-step ea-step-calendar">
            <div class="ea-wizard-calendar"></div>
        </div>
        <div class="ea-wizard-step ea-step-timeslots">
            <div class="ea-wizard-time-slots"><a href="#" class="time-value" data-val="07:00">07:00 am</a><a href="#" class="time-value" data-val="07:45">07:45 am</a><a href="#" class="time-value" data-val="08:30">08:30 am</a><a href="#" class="time-value" data-val="09:15">09:15 am</a><a href="#" class="time-value" data-val="10:00">10:00 am</a><a href="#" class="time-value" data-val="10:45">10:45 am</a><a href="#" class="time-value" data-val="11:30">11:30 am</a><a href="#" class="time-value" data-val="12:15">12:15 pm</a><a href="#" class="time-value" data-val="13:00">01:00 pm</a><a href="#" class="time-value" data-val="13:45">01:45 pm</a><a href="#" class="time-value" data-val="14:30">02:30 pm</a><a href="#" class="time-value" data-val="15:15">03:15 pm</a><a href="#" class="time-value" data-val="16:00">04:00 pm</a><a href="#" class="time-value" data-val="16:45">04:45 pm</a><a href="#" class="time-value" data-val="17:30">05:30 pm</a></div>
        </div>
        <div class="ea-wizard-step ea-step-personal-info">PERSONAL INFO</div>
        <div class="ea-wizard-step ea-step-overview">OVERVIEW</div>
        <div class="ea-wizard-step ea-step-thanks">THANKS</div>
      </div>

      <div class="ea-wizard-actions">
        <button class="ea-wizard-prev btn btn-primary" style="display: none">Prev</button>
        <button class="ea-wizard-next btn btn-primary">Next</button>
        <button class="ea-wizard-submit btn btn-primary" style="display: none">Submit</button>
        <button class="ea-wizard-close btn btn-primary" style="display: none">Close</button>
      </div>

    </div>
  </div>
</template>