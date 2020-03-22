<template id="ea-wizard-template">
    <div class="ea-wizard-overlay">
        <div class="ea-wizard">
            <div class="ea-wizard-header">
                <h3>TITLE</h3>
                <div class="ea-wizard-progress"></div>
            </div>
            <div class="ea-wizard-content">
                <div class="ea-wizard-step current ea-loading">LOADING...</div>
                <div class="ea-wizard-step ea-step-location">LOCATION</div>
                <div class="ea-wizard-step ea-step-worker">WORKER</div>
                <div class="ea-wizard-step ea-step-service">SERVICE</div>
                <div class="ea-wizard-step ea-step-calendar">CALENDAR</div>
                <div class="ea-wizard-step ea-step-timeslots">SLOTS</div>
                <div class="ea-wizard-step ea-step-personal-info">PERSONAL INFO</div>
                <div class="ea-wizard-step ea-step-overview">OVERVIEW</div>
                <div class="ea-wizard-step ea-step-thanks">THANKS</div>
            </div>
            <div class="ea-wizard-actions">
                <button class="ea-wizard-prev" style="display: none">Prev</button>
                <button class="ea-wizard-next">Next</button>
            </div>
        </div>
    </div>
</template>