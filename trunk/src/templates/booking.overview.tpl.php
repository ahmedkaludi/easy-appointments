<script type="text/template" id="ea-appointments-overview">
    <div style="max-width: 500px; margin: 20px auto; padding: 20px; border-radius: 10px; text-align: center; font-family: Arial, sans-serif; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);" class="ea-confirmation-card">
        <h3 style="color: #2b6924; margin-top: 0; display:none;" class="ea-confirmation-title">
            <%- settings['trans.confirmation-title'] || 'Thank You for Booking!' %>
        </h3>
        <div style="margin: 10px 0 20px;">
            <p style="font-size: 15px; color: #333; margin: 0; word-wrap: break-word; white-space: normal; max-width: 100%;" class="ea-confirmation-subtext">
                <%- settings['trans.overview-message'] %>
            </p>
            <p style="font-size: 14px; color: #555; margin-top: 8px; word-wrap: break-word; white-space: normal; max-width: 100%;" class="ea-status-note">
            </p>
        </div>

        <table style="width: 100%; font-size: 14px; color: #000; text-align: left; margin: 0 auto 20px;" class="ea-overview-table">
            <tbody>
                <% if(data.location.indexOf('_') !== 0) { %>
                <tr>
                    <td style="font-weight: bold;">Location:</td>
                    <td><%- data.location %></td>
                </tr>
                <% } %>
                <% if(data.service.indexOf('_') !== 0) { %>
                <tr>
                    <td style="font-weight: bold;">Service:</td>
                    <td><%- data.service %></td>
                </tr>
                <% } %>
                <% if(data.worker.indexOf('_') !== 0) { %>
                <tr>
                    <td style="font-weight: bold;">Worker:</td>
                    <td><%- data.worker %></td>
                </tr>
                <% } %>
                <tr>
                    <td style="font-weight: bold;">Price:</td>
                    <td><%- data.price %><%- settings['trans.currency'] %></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Date & time:</td>
                    <td><%- data.date_time %></td>
                </tr>
            </tbody>
        </table>

        <div id="ea-overview-buttons" style="display: none; justify-content: center; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
            <a 
                href="#" 
                onclick="window.location.reload();" 
                style="padding: 5px 10px; background-color: #333cb7; color: white; text-decoration: none; border-radius: 5px;" 
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

    <div id="ea-total-amount" style="display: none;" data-total="<%- data.price %>"></div>
    <div id="ea-meta-data" 
             data-location="<%- data.location %>" 
             data-service="<%- data.service %>" 
             data-worker="<%- data.worker %>" 
             data-date-time="<%- data.date_time %>" 
             data-currency="<%- settings['trans.currency'] %>"></div>
</script>
