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
        "click .tab-selection a": "tabClicked"
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
            elements : 'mail-template',
            theme: "modern",
            skin: "lightgray",
            menubar : false,
            statusbar : false,
            relative_urls : false,
            remove_script_host : false,
            toolbar: [
                "bold,italic,alignleft,aligncenter,alignright,bullist,numlist,outdent,indent,undo,redo,link,unlink,code"
            ],
            plugins : "wordpress,wplink,paste,-code",
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

    saveSettings: function() {
        this.updateMailTemplate();

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
        var $row = $btn.closest('th');
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
        var name = $li.data('name');
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

            $html = this.template_options({item:o});
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
        var name = $li.data('name');
        var element = this.fields.findWhere({label:name});

        var options = [];

        $li.find('.select-options > li').each(function(index, el) {
            options.push(jQuery(el).text().trim());
        });

        element.set('label', $li.find('.field-label').val());
        element.set('required', $li.find('.required').is(":checked"));
        element.set('visible', $li.find('.visible').is(":checked"));

        if ($li.find('.field-mixed').length > 0) {
            element.set('mixed', $li.find('.field-mixed').val());
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
        var name = $li.data('name');
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
        var tabId = $(event.target).addClass('selected').data('tab');

        this.$el.find('#' + prevId).addClass('hidden');
        this.$el.find('#' + tabId).removeClass('hidden');

        return false;
    }
});