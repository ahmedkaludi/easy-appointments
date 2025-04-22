<script type="text/template" id="ea-appointments-overview">
    <small id="ea-overview-message"><%- settings['trans.overview-message'] %></small>
    <table>
        <tbody>
        <% if(settings['rtl'] == '1') { %>
            <% if(data.location.indexOf('_') !== 0) { %>
            <tr class="row-location">
                <td class="ea-label"><%- settings['trans.location'] %></td>
                <td class="value"><%- data.location %></td>
            </tr>
            <% } %>
            <% if(data.service.indexOf('_') !== 0) { %>
            <tr class="row-service">
                <td class="ea-label"><%- settings['trans.service'] %></td>
                <td class="value"><%- data.service %></td>
            </tr>
            <% } %>
            <% if(data.worker.indexOf('_') !== 0) { %>
            <tr class="row-worker">
                <td class="ea-label"><%- settings['trans.worker'] %></td>
                <td class="value"><%- data.worker %></td>
            </tr>
            <% } %>
            <% if (settings['price.hide'] !== '1') { %>
            <tr class="row-price">
                <td class="ea-label"><%- settings['trans.price'] %></td>
                <td class="value"><%- data.price%> <%- settings['trans.currency'] %></td>
            </tr>
            <% } %>
            <tr class="row-datetime">
                <td class="ea-label"><%- settings['trans.date-time'] %></td>
                <td class="value"><%- data.date %> <%- data.time %></td>
            </tr>
        <% } else { %>
            <% if(data.location.indexOf('_') !== 0) { %>
            <tr class="row-location">
                <td class="ea-label"><%- settings['trans.location'] %></td>
                <td class="value"><%- data.location %></td>
            </tr>
            <% } %>
            <% if(data.service.indexOf('_') !== 0) { %>
            <tr class="row-service">
                <td class="ea-label"><%- settings['trans.service'] %></td>
                <td class="value"><%- data.service %></td>
            </tr>
            <% } %>
            <% if(data.worker.indexOf('_') !== 0) { %>
            <tr class="row-worker">
                <td class="ea-label"><%- settings['trans.worker'] %></td>
                <td class="value"><%- data.worker %></td>
            </tr>
            <% } %>
            <% if (settings['price.hide'] !== '1') { %>
            <tr class="row-price">
                <td class="ea-label"><%- settings['trans.price'] %></td>
                <% if (settings['currency.before'] == '1') { %>
                <td class="value"><%- settings['trans.currency'] %><%- data.price %></td>
                <% } else { %>
                <td class="value"><%- data.price %><%- settings['trans.currency'] %></td>
                <% } %>
            </tr>
            <% } %>
            <tr class="row-datetime">
                <td class="ea-label"><%- settings['trans.date-time'] %></td>
                <td class="value"><%- data.date_time %></td>
            </tr>
        <% } %>
        </tbody>
    </table>
    <div id="ea-total-amount" style="display: none;" data-total="<%- data.price %>"></div>
    <div id="ea-meta-data" 
             data-location="<%- data.location %>" 
             data-service="<%- data.service %>" 
             data-worker="<%- data.worker %>" 
             data-date-time="<%- data.date_time %>" 
             data-currency="<%- settings['trans.currency'] %>"></div>
    
    <div id="ea-success-box" style="display:none; max-width: 500px; margin: 20px auto; padding: 20px; border-radius: 10px; text-align: center; font-family: Arial, sans-serif; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);" class="ea-confirmation-card">
        <h3 style="color: #2b6924; margin-top: 0;" class="ea-confirmation-title">
            <%- settings['trans.confirmation-title'] || 'Thank You for Booking!' %>
        </h3>
        <div style="margin: 10px 0 20px;">
            <p style="font-size: 14px; color: #555; margin-top: 8px; word-wrap: break-word; white-space: normal; max-width: 100%;" class="ea-status-note">
            </p>
        </div>

        <div id="ea-overview-details" style="width: 100%; font-size: 14px; color: #000; text-align: left; margin: 0 auto 20px;">
        </div>

        <div id="ea-overview-buttons" style="justify-content: center; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
            <a 
                href="#" 
                onclick="window.location.reload();" 
                style="padding: 5px 10px; background-color: #333cb7; color: white; text-decoration: none; border-radius: 5px; margin-right: 5px;" 
                class="ea-button-book-again">
                <%- settings['trans.book-again'] || 'Book New Appointment' %>
            </a>

            <a 
                id="ea-add-to-calendar" 
                href="#" 
                target="_blank" 
                style="background-color: #34A853; color: #fff; padding: 5px 10px; border-radius: 6px; text-decoration: none;">
                Add to Google Calendar
            </a>
        </div>
    </div>
</script>
