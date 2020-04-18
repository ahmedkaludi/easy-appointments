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
        <div class="ea-wizard-step ea-step-location current">LOCATION</div>
        <div class="ea-wizard-step ea-step-worker">WORKER</div>
        <div class="ea-wizard-step ea-step-service">SERVICE</div>
        <div class="ea-wizard-step ea-step-calendar">CALENDAR</div>
        <div class="ea-wizard-step ea-step-timeslots">SLOTS</div>
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