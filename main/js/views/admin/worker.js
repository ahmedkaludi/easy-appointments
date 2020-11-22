/**
 * Worker main view
 */
EA.WorkerView = Backbone.View.extend({
    
    tagName:  "tr",

    // show template
    template_show : _.template( jQuery("#ea-tpl-worker-row").html() ),
    
    // edit template
    template_edit : _.template( jQuery("#ea-tpl-worker-row-edit").html() ),

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
        this.$el.attr('tabindex', '0');

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
        var worker = this.model;
        var view = this;

        jQuery.each(this.$el.find('input'), function(index, elem){
            worker.set(jQuery(elem).data('prop'), jQuery(elem).val());
        });

        if (!this.checkEmailAddress(worker.get('email'))) {
            return;
        }

        this.parent.showMessage('Saving...');

        // Saves Worker
        worker.save(null, {
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

    /**
     * Validate email address for Worker
     *
     * @param email
     * @returns {boolean}
     */
    checkEmailAddress: function(email) {

        var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var valid = re.test(email);

        if (!valid) {
            var emailField = this.$el.find('[data-prop="email"]');

            emailField.css({border: '2px solid #ff0000'}).animate({
                    backgroundColor: 'white'
                }, 2000, function() {
                emailField.css({border: '1px solid #ccc'});
            });
        }

        return valid;
    },

    cancel: function() {
        // If is new remove model/view
        if (this.model.isNew()) {
            this.parent.showMessage('New canceled');

            this.model.destroy();
            this.remove();

        } else {
            this.parent.showMessage('Edit canceled');

            this.$el.removeClass('ea-editing');

            this.template = this.template_show;
            this.render();
        }

        this.edit_mode = false;
    },

    // Delets model and view
    removeItem: function() {
        var view = this;

        if(confirm('Are you sure?')) {
            view.parent.showMessage('Deleting...');

            view.model.destroy({
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