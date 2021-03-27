// Main tamplate
EA.CustumizeView = Backbone.View.extend({

    template : _.template( jQuery("#ea-tpl-custumize").html() ),
    template_fields : _.template(jQuery("#ea-tpl-custom-forms").html()),
    template_options : _.template(jQuery("#ea-tpl-custom-form-options").html()),

    tinymceOn : true,

    events: {
        "click .btn-save-settings" : "saveSettings",
        "click .btn-add-field" : "addCustomFiled",
        "click .single-field-options" : "fieldOptions",
        "click .add-select-option" : "addSelectOption",
        "click .item-save" : "apply",
        "click .item-delete": "deleteOption",
        "click .remove-select-option": "removeSelectedOption",
        "click .mail-tab": "selectMailNotification",
        "click .tab-selection a": "tabClicked",
        "click .btn-add-redirect": "addAdvanceRedirect",
        "click .remove-advance-redirect": "removeAdvanceRedirect",
        "click .btn-add-cancel-redirect": "addAdvanceCancelRedirect",
        "click .remove-advance-cancel-redirect": "removeAdvanceCancelRedirect",
        "change #ea-select-status": "defaultStatusChange",
        "click .form-label-option": "changeFormLabelStyle",
        "click .select-label-option": "changeSelectLabelStyle",
        "click .btn-gdpr-delete-data": "gdprDeleteData",
    },

    initialize: function () {
        var plugin = this;

        this.collection = new EA.Settings();

        this.fields = new EA.Fields();
        this.fields.comparator = 'position';

        // Table draw
//      this.render();

        var defOptions = jQuery.Deferred();
        var defFields = jQuery.Deferred();

        // plugin.collection.bind("reset", this.render, this);
        // plugin.fields.bind("reset", this.renderFields, this);

        jQuery.when(defOptions, defFields).done(function (d1, d2) {
            plugin.render();

            plugin.showCustomRedirects();
            plugin.showCustomCancelRedirects();
        });

        // if there is no data in cache
        this.collection.fetch( {
            reset:true,
            success: function(collection, response, options) {
                defOptions.resolve();
            }
        });

        this.fields.fetch( {
            reset:true,
            success: function(collection, response, options) {
                defFields.resolve();
            }
        } );
    },

    render: function () {
        var obj = this;
        this.$el.empty(); // clear the element to make sure you don't double your contact view

        var content = this.template( { settings : this.collection.toJSON() } );

        this.$el.html( content );

        this.renderFields();

        this.$el.find('#custom-fields').sortable({
            placeholder: 'sortable-placeholder',
            update : function(event, ui) {
                obj.reorder();
            }
        });
        //this.$el.find('#custom-fields').disableSelection();

        // init tiny mce
        this.initTinyMCE();

        // render status change
        this.defaultStatusChange();

        this.changeFormLabelStyleInit();
        this.changeSelectLabelStyleInit();

        return this;
    },

    /**
     *
     */
    initTinyMCE: function() {

        if (typeof tinymce === 'undefined') {
            this.tinymceOn = false;
            return;
        }

        tinymce.init( {
            mode : "exact",
            elements : 'mail-template,fullcalendar-event-template',
            theme: "modern",
            skin: "lightgray",
            height : "250",
            menubar : false,
            statusbar : false,
            relative_urls : false,
            remove_script_host : false,
            toolbar: [
                "bold,italic,alignleft,aligncenter,alignright,bullist,numlist,outdent,indent,image,media,undo,redo,link,unlink,code"
            ],
            plugins : "wordpress,image,media,wplink,paste,-code",
            paste_auto_cleanup_on_paste : true,
            paste_postprocess : function( pl, o ) {
                o.node.innerHTML = o.node.innerHTML.replace( /&nbsp;+/ig, " " );
            }
        } );
    },

    selectMailNotification: function(event) {
        // save previous content from tinyMCE
        this.updateMailTemplate();

        // process new content
        var $newTemplate = jQuery(event.target);
        var newContent = this.$el.find($newTemplate.data('textarea')).val();

        if (this.tinymceOn) {
            tinymce.get('mail-template').setContent(newContent);

            // clear the stack of undo
            tinymce.activeEditor.undoManager.clear();
        } else {
            this.$el.find('#mail-template').val(newContent);
        }

        this.$el.find('.mail-tab').filter('.selected').removeClass('selected');
        $newTemplate.addClass('selected');
    },

    updateMailTemplate: function() {
        var prevContent = '';
        var $prevTemplate = this.$el.find('.mail-tab').filter('.selected');
        if (this.tinymceOn) {
            prevContent = tinymce.get('mail-template').getContent();
        } else {
            prevContent = this.$el.find('#mail-template').val();
        }
        this.$el.find($prevTemplate.data('textarea')).val(prevContent);
    },

    updateFullCalendarTemplate: function() {
        if (!this.tinymceOn) {
            return;
        }

        var template = tinymce.get('fullcalendar-event-template').getContent();
        this.$el.find('#fullcalendar-event-template').val(template);
    },

    saveSettings: function() {
        this.updateMailTemplate();
        this.updateFullCalendarTemplate();

        var fields = this.$el.find('.field');

        var that = this;

        // list of options that are processed
        var processed = [];

        // update the collection
        this.collection.each(function(model, index) {
            var key = model.get('ea_key');

            // mark for removing
            model.set('for_delete', true);

            if (processed.indexOf(key) !== -1) {
                return;
            }

            var input = fields.filter('[data-key="' + key + '"]');

            if(input.is('[type="checkbox"]')) {
                if(input.is(':checked')) {
                    model.set('ea_value', 1);
                } else {
                    model.set('ea_value', 0);
                }

                model.unset('for_delete', {silent:true});
            } else {
                model.set('ea_value', input.val());
                model.unset('for_delete', {silent:true});
            }

            // mark as processed
            processed.push(key);
        });

        var collectionToBeDeleted = [];

        // clear down the section
        this.collection.each(function(model, index) {

            if (model.get('for_delete')) {
                collectionToBeDeleted.push(model);
            }

        });

        this.collection.remove(collectionToBeDeleted, {silent:true});

        var wrapper = new EA.SettingsWrapper({options: this.collection, fields: this.fields});
        wrapper.save( null, {
            error: function(response){
                alert('There has been some error. Please try later.');
            },
            success: function(){
                alert('Settings saved!');
            }
        });
    },

    addCustomFiled: function(e) {
        var obj = this;
        var $btn = jQuery(e.currentTarget);
        var $row = $btn.closest('div');
        var name = $row.find('input').val();
        var type = $row.find('select').val();

        var field = new EA.Field({
            label:name,
            type:type,
            position: obj.fields.length + 1
        });

        this.fields.add(field);

        var $html = this.template_fields({item : field.toJSON()});
        $ul = this.$el.find('#custom-fields');
        $ul.append($html);

        $row.find('input').val('');

        $ul.find('.single-field-options:last').click();
    },

    renderFields: function() {
        var obj = this, $ul, tags = [];

        $ul = this.$el.find('#custom-fields');

        $ul.empty();

        this.fields.sort();

        this.fields.each(function(model, index) {
            var o = model.toJSON();

            var $html = obj.template_fields({item : o});
            $ul.append($html);

            tags.push('#' + o.slug + '#');
        });

        this.$el.find('#custom-tags').html(tags.join(', '));
    },

    fieldOptions: function(e) {
        e.preventDefault();
        var $btn = jQuery(e.currentTarget);
        var $li = $btn.closest('li');
        var name = '' + $li.data('name');
        var element = this.fields.findWhere({label: name});

        if ($btn.find('i').hasClass('fa-chevron-down')) {
            // open
            $btn.find('i').removeClass('fa-chevron-down');
            $btn.find('i').addClass('fa-chevron-up');

            var o = element.toJSON();

            if (o.type === 'SELECT') {
                if (o.mixed !== '' ) {
                    o.options = o.mixed.split(',');
                } else {
                    o.options = ['-'];
                }
            }

            $html = $(this.template_options({item:o}));

            if (o.type === 'PHONE') {
                $html.find('.field-default_value').val(o.default_value);
            }

            $li.append($html);

            this.$el.find('#custom-fields').sortable('disable');

            $li.find('.select-options').sortable();
        } else {
            // close
            $btn.find('i').removeClass('fa-chevron-up');
            $btn.find('i').addClass('fa-chevron-down');
            $li.find('.field-settings').remove();
            this.$el.find('#custom-fields').sortable('enable');
        }

        return false;
    },

    addSelectOption: function(e) {
        e.preventDefault();
        var $btn = jQuery(e.currentTarget);
        var value = $btn.prevAll('input').val();
        var cont = $btn.closest('.field-settings');

        cont.find('.select-options').append('<li data-element="'+ value + '">'+ value + '<a href="#" class="remove-select-option"><i class="fa fa-trash-o"></i></a></li>');

        // delete option
        $btn.prevAll('input').val('');
    },

    apply: function(e) {
        e.preventDefault();

        var $btn = jQuery(e.currentTarget);
        var $li = $btn.closest('li');
        var name = '' + $li.data('name');
        var element = this.fields.findWhere({ label: name });

        var options = [];

        $li.find('.select-options > li').each(function(index, el) {
            options.push(jQuery(el).text().trim());
        });

        element.set('label', $li.find('.field-label').val());
        element.set('slug', $li.find('.field-slug').val());
        element.set('required', $li.find('.required').is(":checked"));
        element.set('visible', $li.find('.visible').is(":checked"));

        if ($li.find('.field-mixed').length > 0) {
            element.set('mixed', $li.find('.field-mixed').val());
        }

        if ($li.find('.field-default_value').length > 0) {
            element.set('default_value', $li.find('.field-default_value').val());
        }

        if (options.length > 0) {
            element.set('mixed', options.join(','));
        }

        $li.closest('ul').sortable('enable');

        element.save( null, {
            error: function(response){
                alert('There has been some error.');
            }
        });


        this.renderFields();
    },

    deleteOption: function(e) {
        e.preventDefault();

        var obj = this;

        var $btn = jQuery(e.currentTarget);
        var $li = $btn.closest('li');
        var name = '' + $li.data('name');
        var element = this.fields.findWhere({label:name});

        this.fields.remove(element);

        element.destroy({
            success: function(model, response) {
                obj.renderFields();
            },
            error: function() {
                alert('Error on delete!');
            }
        });
    },

    removeSelectedOption: function(e) {
        e.preventDefault();
        var $btn = jQuery(e.currentTarget);

        $btn.closest('li').remove();
    },

    addAdvanceRedirect: function() {
        var $elData = this.$el.find('#advance-redirect');
        var data = JSON.parse($elData.val());

        if (!Array.isArray(data)) {
            data = [];
        }

        var newItem = {
            service: this.$el.find('#redirect-service').val(),
            url: this.$el.find('#redirect-url').val()
        };

        data.push(newItem);

        $elData.val(JSON.stringify(data));

        this.showCustomRedirects();
    },

    removeAdvanceRedirect: function(e) {
        var $btn = jQuery(e.currentTarget);
        var index = $btn.data('index');

        var $elData = this.$el.find('#advance-redirect');
        var data = JSON.parse($elData.val());

        data.splice(index, 1);

        $elData.val(JSON.stringify(data));

        this.showCustomRedirects();
    },

    showCustomRedirects: function() {
        var $list = this.$el.find('#custom-redirect-list');
        var $ulData = this.$el.find('#advance-redirect');
        var data = JSON.parse($ulData.val());

        if (!Array.isArray(data)) {
            data = [];
        }

        $list.empty();

        jQuery.each(data, function(index, element) {
            var service = eaData.Services.find(function(el) {
               return el.id === element.service;
            });

            if (!service) {
                service = {
                    name: 'REMOVED'
                };
            }

            $list.append('<div class="list-item redirect-row"><span class="row-no">' + (index+1) + '.</span><span class="redirect-service-name">' + service.name + '</span><span class="redirect-url">' + element.url + '</span><button data-index="' + index + '" class="button button-primary remove-advance-redirect"> X </button></div>');
        });
    },


    addAdvanceCancelRedirect: function() {
        var $elData = this.$el.find('#advance-cancel-redirect');
        var data = JSON.parse($elData.val());

        if (!Array.isArray(data)) {
            data = [];
        }

        var newItem = {
            service: this.$el.find('#cancel-redirect-service').val(),
            url: this.$el.find('#cancel-redirect-url').val()
        };

        data.push(newItem);

        $elData.val(JSON.stringify(data));

        this.showCustomCancelRedirects();
    },

    removeAdvanceCancelRedirect: function(e) {
        var $btn = jQuery(e.currentTarget);
        var index = $btn.data('index');

        var $elData = this.$el.find('#advance-cancel-redirect');
        var data = JSON.parse($elData.val());

        data.splice(index, 1);

        $elData.val(JSON.stringify(data));

        this.showCustomCancelRedirects();
    },

    showCustomCancelRedirects: function() {
        var $list = this.$el.find('#custom-cancel-redirect-list');
        var $ulData = this.$el.find('#advance-cancel-redirect');
        var data = JSON.parse($ulData.val());

        if (!Array.isArray(data)) {
            data = [];
        }

        $list.empty();

        jQuery.each(data, function(index, element) {
            var service = eaData.Services.find(function(el) {
                return el.id === element.service;
            });

            if (!service) {
                service = {
                    name: 'REMOVED'
                };
            }

            $list.append('<div class="list-item redirect-row"><span class="row-no">' + (index+1) + '.</span><span class="redirect-service-name">' + service.name + '</span><span class="redirect-url">' + element.url + '</span><button data-index="' + index + '" class="button button-primary remove-advance-cancel-redirect"> X </button></div>');
        });
    },

    reorder: function() {
        var obj = this;
        var $ul = this.$el.find('#custom-fields');

        var $lis = $ul.children();

        var count = 1;

        $lis.each(function(index, el) {
            var name = jQuery(el).data('name');
            var element = obj.fields.findWhere({label:name});

            element.set('position', count++);
        });
    },

    destroy_view: function() {
        tinymce.remove('#mail-template');

        // COMPLETELY UNBIND THE VIEW
        this.undelegateEvents();

        this.$el.removeData().unbind();

        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },

    tabClicked: function (event) {
        event.stopPropagation();

        // get previous selected
        var prevId = this.$el.find('.tab-selection .selected').removeClass('selected').data('tab');
        // get next selected
        var target = $(event.target);

        if (target.prop('tagName').toLowerCase() === 'span') {
            target = $(event.target).closest('a');
        } 
        var tabId = target.addClass('selected').data('tab');

        this.$el.find('#' + prevId).addClass('hidden');
        this.$el.find('#' + tabId).removeClass('hidden');

        return false;
    },

    defaultStatusChange: function () {
        var status = jQuery('#ea-select-status').val();

        if (status === 'reservation') {
            jQuery('#ea-select-status-notification').show();
            return;
        }

        jQuery('#ea-select-status-notification').hide();
    },

    changeFormLabelStyle: function (e) {
        var selected = jQuery(e.currentTarget);
        var value = selected.data('value');

        this.$el.find('[name="form.label.above"]').val(value);

        this.$el.find('.form-label-option').toggleClass('selected');

    },

    changeSelectLabelStyle: function (e) {
        var selected = jQuery(e.currentTarget);
        var value = selected.data('value');

        this.$el.find('[name="label.from_to"]').val(value);

        this.$el.find('.select-label-option').toggleClass('selected');

    },

    changeFormLabelStyleInit: function () {
        var initValue = this.$el.find('[name="form.label.above"]').val();

        if (initValue !== '1') {
            this.$el.find('.form-label-option').first().addClass('selected');
            return;
        }

        this.$el.find('.form-label-option').last().addClass('selected');
    },

    changeSelectLabelStyleInit: function () {
        var initValue = this.$el.find('[name="label.from_to"]').val();

        if (initValue === '1') {
            this.$el.find('.select-label-option').first().addClass('selected');
            return;
        }

        this.$el.find('.select-label-option').last().addClass('selected');
    },

    gdprDeleteData: function() {
        if (!confirm("Are you sure?")) {
            return;
        }

        var endpoint = ea_settings.rest_url + 'easy-appointments/v1/gdpr?_wpnonce=' + wpApiSettings.nonce;
        jQuery.ajax({
            url: endpoint,
            type: 'DELETE',
            success: function(result) {
                alert(result);
            }
        });
    }
});