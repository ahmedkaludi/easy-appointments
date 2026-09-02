<?php
// phpcs:disable Generic.PHP.DisallowAlternativePHPTags
defined( 'ABSPATH' ) || exit;
?>
<style>
    body .wp-block-post-content a:not(.wp-element-button) {
        color: #000000;
        text-decoration: none !important;
    }
    .ea-standard .selected-time {
        background-color: green !important;
        color: white !important;
    }

</style>
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
            <% if(data.service_description) { %>
                <% if(data.service_description.length > 0) { %>
                <tr class="row-service-description">
                    <td class="ea-label"><%- settings['trans.description'] || 'Description' %></td>
                    <td class="value" style="white-space: pre-line;"><%= data.service_description %></td>
                </tr>
                <% } %>
            <% } %>
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
                <td class="value"><%- settings['hide.decimal_in_price'] == '1' ? Math.round(parseFloat(data.price)) : data.price %> <%- settings['trans.currency'] %></td>
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
            <% if(data.service_description) { %>
                <% if(data.service_description.length > 0) { %>
                <tr class="row-service-description">
                    <td class="ea-label"><%- settings['trans.description'] || 'Description' %></td>
                    <td class="value" style="white-space: pre-line;"><%= data.service_description %></td>
                </tr>
                <% } %>
            <% } %>
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
                <td class="value"><%- settings['trans.currency'] %><%- settings['hide.decimal_in_price'] == '1' ? Math.round(parseFloat(data.price)) : data.price %></td>
                <% } else { %>
                <td class="value"><%- settings['hide.decimal_in_price'] == '1' ? Math.round(parseFloat(data.price)) : data.price %><%- settings['trans.currency'] %></td>
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
    
    <div id="ea-success-box" style="display:none; width:100%; max-width:600px; margin: 0 auto; padding: 10px 0; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: transparent; border: none; box-shadow: none;" class="ea-confirmation-card">
        <div style="display: flex; justify-content: center; margin: 4px 0 16px;">
            <div style="background: #f0fdf4; color: #16a34a; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #bbf7d0;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="fill:none !important; stroke:#16a34a !important; width:32px !important; height:32px !important;">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
        </div>
        <h3 style="color: #0f172a !important; margin: 0 0 10px 0 !important; font-size: 22px !important; font-weight: 700 !important; letter-spacing: -0.01em !important; display: block !important; visibility: visible !important;" class="ea-confirmation-title">
            <%- settings['trans.confirmation-title'] || 'Thank You for Booking!' %>
        </h3>
        <div style="margin: 0 0 20px 0;">
            <p style="font-size: 14.5px; color: #475569; line-height: 1.6; margin: 0; word-wrap: break-word; white-space: normal; max-width: 100%; display: block !important; visibility: visible !important;" class="ea-status-note">
            </p>
        </div>

        <div id="ea-overview-details" style="width: 100%; font-size: 14px; color: #1e293b; text-align: left; margin: 0 auto 20px; background: #f8fafc; border-radius: 12px; padding: 18px 20px; border: 1px solid #e2e8f0; box-sizing: border-box;">
        </div>

        <div id="ea-overview-buttons" style="display: flex; justify-content: center; align-items: center; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
            <a 
                href="#" 
                onclick="window.location.reload();" 
                style="padding: 10px 20px; background: #2563eb; color: #ffffff !important; text-decoration: none !important; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2); transition: all 0.2s ease;" 
                class="ea-button-book-again">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="fill:none !important; stroke:#ffffff !important; width:16px !important; height:16px !important; flex-shrink:0;"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                <span><%- settings['trans.book-again'] || 'Book New Appointment' %></span>
            </a>
            <a 
                id="ea-add-to-calendar" 
                href="#" 
                target="_blank" 
                style="padding: 10px 20px; background: #16a34a; color: #ffffff !important; text-decoration: none !important; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2); transition: all 0.2s ease;">                
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="fill:none !important; stroke:#ffffff !important; width:16px !important; height:16px !important; flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <span><%- settings['trans.add-to-calendar'] || 'Add to Google Calendar' %></span>
            </a>
        </div>
    </div>
</script>
<?php
// phpcs:enable Generic.PHP.DisallowAlternativePHPTags
?>
