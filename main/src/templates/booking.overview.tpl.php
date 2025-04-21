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
            <% if(settings['rtl'] == '1') { %>
                <% if(data.location.indexOf('_') !== 0) { %>
                <tr class="row-location">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.location'] %>:</td>
                    <td class=""><%- data.location %></td>
                </tr>
                <% } %>
                <% if(data.service.indexOf('_') !== 0) { %>
                <tr class="row-service">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.service'] %>:</td>
                    <td class=""><%- data.service %></td>
                </tr>
                <% } %>
                <% if(data.worker.indexOf('_') !== 0) { %>
                <tr class="row-worker">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.worker'] %>:</td>
                    <td class=""><%- data.worker %></td>
                </tr>
                <% } %>
                <% if (settings['price.hide'] !== '1') { %>
                <tr class="row-price">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.price'] %>:</td>
                    <td class=""><%- data.price%> <%- settings['trans.currency'] %></td>
                </tr>
                <% } %>
                <tr class="row-datetime">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.date-time'] %>:</td>
                    <td class=""><%- data.date %> <%- data.time %></td>
                </tr>
            <% } else { %>
                <% if(data.location.indexOf('_') !== 0) { %>
                <tr class="row-location">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.location'] %>:</td>
                    <td class=""><%- data.location %></td>
                </tr>
                <% } %>
                <% if(data.service.indexOf('_') !== 0) { %>
                <tr class="row-service">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.service'] %>:</td>
                    <td class=""><%- data.service %></td>
                </tr>
                <% } %>
                <% if(data.worker.indexOf('_') !== 0) { %>
                <tr class="row-worker">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.worker'] %>:</td>
                    <td class=""><%- data.worker %></td>
                </tr>
                <% } %>
                <% if (settings['price.hide'] !== '1') { %>
                <tr class="row-price">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.price'] %>:</td>
                    <% if (settings['currency.before'] == '1') { %>
                    <td class=""><%- settings['trans.currency'] %><%- data.price %></td>
                    <% } else { %>
                    <td class=""><%- data.price %><%- settings['trans.currency'] %></td>
                    <% } %>
                </tr>
                <% } %>
                <tr class="row-datetime">
                    <td style="font-weight: bold;" class="ea-label"><%- settings['trans.date-time'] %>:</td>
                    <td class=""><%- data.date_time %></td>
                </tr>
            <% } %>
            </tbody>
        </table>

        <a href="#" onclick="window.location.reload();" style="display: none; padding: 10px 20px; background-color: #333cb7; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;" class="ea-button-book-again">
            <%- settings['trans.book-again'] || 'Book Another Appointment' %>
        </a>
    </div>

    <div id="ea-total-amount" style="display: none;" data-total="<%- data.price %>"></div>
</script>
