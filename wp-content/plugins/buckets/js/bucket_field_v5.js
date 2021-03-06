(function($){

    acf.fields.buckets = acf.field.extend({

        type: 'buckets',

        $el: null,
        $input: null,
        $filters: null,
        $choices: null,
        $values: null,

        actions: {
            'ready':    'initialize',
            'append':   'initialize'
        },

        events: {
            'keypress [data-filter]':           'submit_filter',
            'change [data-filter]':             'change_filter',
            'keyup [data-filter]':              'change_filter',
            'click .choices .acf-rel-item':     'add_item',
            'click [data-name="remove_item"]':  'remove_item'
        },

        focus: function(){

            // get elements
            this.$el = this.$field.find('.acf-buckets');
            this.$input = this.$el.find('.acf-hidden input');
            this.$choices = this.$el.find('.choices'),
            this.$values = this.$el.find('.values');

            // get options
            this.o = acf.get_data( this.$el );

        },

        initialize: function(){

            // reference
            var self = this,
                $field = this.$field,
                $el = this.$el,
                $input = this.$input;


            // right sortable
            this.$values.children('.list').sortable({

                items:                  'li',
                forceHelperSize:        true,
                forcePlaceholderSize:   true,
                scroll:                 true,

                update: function(){

                    $input.trigger('change');

                }

            });


            this.$choices.children('.list').scrollTop(0).on('scroll', function(e){

                // bail early if no more results
                if( $el.hasClass('is-loading') || $el.hasClass('is-empty') ) {

                    return;

                }


                // Scrolled to bottom
                if( Math.ceil( $(this).scrollTop() ) + $(this).innerHeight() >= $(this).get(0).scrollHeight ) {

                    // get paged
                    var paged = $el.data('paged') || 1;


                    // update paged
                    $el.data('paged', (paged+1) );


                    // fetch
                    self.set('$field', $field).fetch();

                }

            });


            /*
var scroll_timer = null;
            var scroll_event = function( e ){

                console.log( 'scroll_event' );

                if( scroll_timer) {

                    clearTimeout( scroll_timer );

                }


                scroll_timer = setTimeout(function(){


                    if( $field.is(':visible') && acf.is_in_view($field) ) {

                        // fetch
                        self.doFocus($field);
                        self.fetch();


                        $(window).off('scroll', scroll_event);

                    }


                }, 100);


            };


            $(window).on('scroll', scroll_event);

*/
            // ajax fetch values for left side
            this.fetch();

        },

        maybe_fetch: function(){

            // reference
            var self = this,
                $field = this.$field;


            // abort timeout
            if( this.o.timeout ) {

                clearTimeout( this.o.timeout );

            }


            // fetch
            var timeout = setTimeout(function(){

                self.doFocus($field);
                self.fetch();

            }, 300);

            this.$el.data('timeout', timeout);

        },

        fetch: function(){

            // reference
            var self = this,
                $field = this.$field;


            // add class
            this.$el.addClass('is-loading');


            // abort XHR if this field is already loading AJAX data
            if( this.o.xhr ) {

                this.o.xhr.abort();
                this.o.xhr = false;

            }


            // add to this.o
            this.o.action = 'acf/fields/relationship/query';
            this.o.field_key = $field.data('key');
            this.o.post_id = acf.get('post_id');


            // ready for ajax
            var ajax_data = acf.prepare_for_ajax( this.o );


            // clear html if is new query
            if( ajax_data.paged == 1 ) {

                this.$choices.children('.list').html('')

            }


            // add message
            //this.$choices.children('.list').append('<p>' + acf._e('relationship', 'loading') + '...</p>');
            this.$choices.find('ul:last').append('<p><i class="acf-loading"></i> ' + acf._e('relationship', 'loading') + '</p>');

            // get results
            var xhr = $.ajax({
                url:        acf.get('ajaxurl'),
                dataType:   'json',
                type:       'post',
                data:       ajax_data,
                success:    function( json ){

                    self.set('$field', $field).render( json );

                }
            });


            // update el data
            this.$el.data('xhr', xhr);

        },

        render: function( json ){

            // remove loading class
            this.$el.removeClass('is-loading is-empty');


            // remove p tag
            this.$choices.find('p').remove();


            // no results?
            if( !json || !json.results || !json.results.length ) {

                // add class
                this.$el.addClass('is-empty');


                // add message
                if( this.o.paged == 1 ) {

                    this.$choices.children('.list').append('<p>' + acf._e('relationship', 'empty') + '</p>');

                }


                // return
                return;

            }


            // get new results
            var $new = $( this.walker(json.results) );


            // apply .disabled to left li's
            this.$values.find('.acf-rel-item').each(function(){

                $new.find('.acf-rel-item[data-id="' +  $(this).data('id') + '"]').addClass('disabled');

            });


            // underline search match
            // consider removing due to bug where matched strings within HTML attributes caused incorrect results
            // Looks like Select2 v4 has moved away from highlighting results, so perhaps we should too
            if( this.o.s ) {

                var s = this.o.s;

                $new.find('.acf-rel-item').each(function(){

                    // vars
                    var find = $(this).text(),
                        replace = find.replace( new RegExp('(' + s + ')', 'gi'), '<b>$1</b>');

                    $(this).html( $(this).html().replace(find, replace) );

                });

            }


            // append
            this.$choices.children('.list').append( $new );


            // merge together groups
            var label = '',
                $list = null;

            this.$choices.find('.acf-rel-label').each(function(){

                if( $(this).text() == label ) {

                    $list.append( $(this).siblings('ul').html() );

                    $(this).parent().remove();

                    return;
                }


                // update vars
                label = $(this).text();
                $list = $(this).siblings('ul');

            });


        },

        walker: function( data ){

            // vars
            var s = '';


            // loop through data
            if( $.isArray(data) ) {

                for( var k in data ) {

                    s += this.walker( data[ k ] );

                }

            } else if( $.isPlainObject(data) ) {

                // optgroup
                if( data.children !== undefined ) {

                    s += '<li><span class="acf-rel-label">' + data.text + '</span><ul class="acf-bl">';

                        s += this.walker( data.children );

                    s += '</ul></li>';

                } else {

                    var edit_url = $('.new-bucket-button').attr('href');
                    var new_url = edit_url.replace('TB_iframe=1', 'bucket_id=' + data.id) + '&TB_iframe=1';

                    s += '<li><span class="acf-rel-item" data-id="' + data.id + '">' + data.text + '</span><span class="edit" data-url="' + new_url + '"><span class="dashicons dashicons-edit"></span></span></li>';

                }

            }


            // return
            return s;

        },

        submit_filter: function( e ){

            // don't submit form
            if( e.which == 13 ) {

                e.preventDefault();

            }

        },

        change_filter: function( e ){

            // vars
            var val = e.$el.val(),
                filter = e.$el.data('filter');


            // Bail early if filter has not changed
            if( this.$el.data(filter) == val ) {

                return;

            }


            // update attr
            this.$el.data(filter, val);


            // reset paged
            this.$el.data('paged', 1);


            // fetch
            if( e.$el.is('select') ) {

                this.fetch();

            // search must go through timeout
            } else {

                this.maybe_fetch();

            }

        },

        add_item: function( e ){

            // max posts
            if( this.o.max > 0 ) {

                if( this.$values.find('.acf-rel-item').length >= this.o.max ) {

                    alert( acf._e('relationship', 'max').replace('{max}', this.o.max) );

                    return;

                }

            }


            // can be added?
            if( e.$el.hasClass('disabled') ) {

                return false;

            }


            // disable
            e.$el.addClass('disabled');


            // template
            var html = [
                '<li>',
                    '<input type="hidden" name="' + this.$input.attr('name') + '[]" value="' + e.$el.data('id') + '" />',
                    '<span data-id="' + e.$el.data('id') + '" class="acf-rel-item">' + e.$el.html(),
                        '<a href="#" class="acf-icon small dark" data-name="remove_item"><span class="dashicons dashicons-minus"></span></a>',
                    '</span>',
                '</li>'].join('');


            // add new li
            this.$values.children('.list').append( html )


            // trigger change on new_li
            this.$input.trigger('change');


            // validation
            acf.validation.remove_error( this.$field );

        },

        remove_item : function( e ){

            // vars
            var $span = e.$el.parent(),
                id = $span.data('id');


            // remove
            $span.parent('li').remove();


            // show
            this.$choices.find('.acf-rel-item[data-id="' + id + '"]').removeClass('disabled');


            // trigger change on new_li
            this.$input.trigger('change');

        }

    });


})(jQuery);
