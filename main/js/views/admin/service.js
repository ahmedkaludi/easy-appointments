/**
 * Services main view
 */
EA.ServiceView = Backbone.View.extend({
    
    tagName:  "tr",

    // show template
    template_show : _.template( jQuery("#ea-tpl-services-row").html() ),
    
    // edit template
    template_edit : _.template( jQuery("#ea-tpl-services-row-edit").html() ),

    template : null,

    edit_mode : false,

    events: {
        "click .btn-edit"   : "edit",
        "dblclick"          : "edit",
        "click .btn-del"    : "removeItem",
        "click .btn-save"   : "save",
        "click .btn-cancel" : "cancel",
        "keydown input"     : "keydownEvent"
    },

    initialize: function (options) {
        this.template = this.template_show;

        this.parent = options.parent;
        this.render();
    },

    render: function () {

        var renderedContent = this.template( { row : this.model.toJSON() } );
        
        jQuery(this.el).html( renderedContent );
        
        this.$el.addClass('ea-row');

        return this;
    },

    edit: function() {

        if(this.edit_mode) {
            return;
        }

        // Edit class
        this.$el.addClass('ea-editing');

        this.template = this.template_edit;
        this.render();

        this.$el.find('input:first').focus();

        this.edit_mode = true;
    },

    save: function() {
        var service = this.model;
        var view = this;

        jQuery.each(this.$el.find('input'), function(index, elem){
            service.set(jQuery(elem).data('prop'), jQuery(elem).val());
        });

        this.parent.showMessage('Saving...');

        var message = '(duration / slot_step) must be round number.';

        if (service.get('duration') % service.get('slot_step') !== 0) {
            alert(message);
            return;
        }

        // Saves Service
        service.save(null, {
            success: function(model, response) {
                view.render();
                model.collection.cacheData();
                view.parent.showMessage('Saved...');
            }
        });

        this.$el.removeClass('ea-editing');

        this.template = this.template_show;
        this.render();

        this.edit_mode = false;
    },

    cancel: function() {
        // If is new remove model/view
        if(this.model.isNew()) {
            this.model.destroy();
            this.remove();
            
            this.parent.showMessage('New canceled');

        } else {

            this.$el.removeClass('ea-editing');

            this.template = this.template_show;
            this.render();

            this.parent.showMessage('Edit canceled');
        }

        this.edit_mode = false;
    },

    // Delets model and view
    removeItem: function() {
        var view = this;

        if(confirm('Are you sure?')) {
            view.parent.showMessage('Deleting...');

            this.model.destroy({
                success: function(model, response) {
                    view.remove();
                    view.parent.showMessage('Done...');
                },
                error: function(model, response) {
                    view.parent.showMessage('Error...');
                }
            });
        }
    },

    // 
    keydownEvent: function(e) {
        switch (e.which) {
            // esc
            case 27 :
                this.cancel();
            break;
        }
    }
});