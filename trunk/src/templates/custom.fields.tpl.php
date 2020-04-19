<!-- Template for form fields expect settings object with translation and MetaFields -->
<script id="ea-template-custom-fields" type="text/template">
<% _.each(settings.MetaFields, function(item,key,list) { %>
<% if (item.visible == "0") { return; } %>
<div class="form-group">
    <label class="col-sm-4 control-label"><%= _.escape(item.label) %> <% if (item.required == "1") { %>*<% }
        %> : </label>
    <div class="col-sm-8">
        <!-- INPUT TYPE -->
        <% if(item.type === 'INPUT') { %>
        <input class="form-control custom-field" maxlength="499" type="text" name="<%= item.slug %>" placeholder="<%= _.escape(item.mixed) %>"
        <% if (item.required == "1") { %>data-rule-required="true" data-msg-required="<%=
        settings['trans.field-required'] %>"<% } %> <% if (item.validation == "email") {
        %>data-rule-email="true" data-msg-email="<%= settings['trans.error-email'] %>"<% } %>>
        <!-- PHONE TYPE -->
        <% } else if(item.type === 'PHONE') { %>
        <?php require __DIR__ . '/phone.field.tpl.php';?>
        <!-- EMAIL TYPE -->
        <% } else if(item.type === 'EMAIL') { %>
        <input class="form-control custom-field" maxlength="499" type="text" name="<%= item.slug %>" placeholder="<%= _.escape(item.mixed) %>"
        <% if (item.required == "1") { %>data-rule-required="true" data-msg-required="<%= settings['trans.field-required'] %>"<% } %> data-rule-email="true" data-msg-email="<%= settings['trans.error-email'] %>">
        <!-- SELECT TYPE -->
        <% } else if(item.type === 'SELECT') { %>
        <select class="form-control custom-field" name="<%= item.slug %>" <% if (item.required ==
        "1") { %>aria-required="true" <% if (item.required == "1") { %>data-rule-required="true"<% }
        %> data-msg-required="<%= settings['trans.field-required'] %>"<% } %>>
        <% _.each(item.mixed.split(','),function(i,k,l) { %>
        <% if (i == "-") { %>
        <option value="">-</option>
        <% } else { %>
        <option value="<%= _.escape(i) %>"><%= _.escape(i) %></option>
        <% }});%>
        </select>
        <!-- TEXTAREA TYPE -->
        <% } else if(item.type === 'TEXTAREA') { %>
        <textarea class="form-control custom-field" rows="3" maxlength="499" style="height: auto;"
                  name="<%= item.slug %>" <% if (item.required == "1") { %>data-rule-required="true"
        data-msg-required="<%= settings['trans.field-required'] %>"<% } %>></textarea>
        <% } %>
    </div>
</div>
<% });%>
</script>