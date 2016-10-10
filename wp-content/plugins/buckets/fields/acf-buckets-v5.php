<?php

/*
*  ACF Relationship Field Class
*
*  All the logic for this field type
*
*  @class       acf_field_relationship
*  @extends     acf_field
*  @package     ACF
*  @subpackage  Fields
*/

if( ! class_exists('acf_field_buckets') ) :

class acf_field_buckets extends acf_field {


    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type    function
    *  @date    5/03/2014
    *  @since   5.0.0
    *
    *  @param   n/a
    *  @return  n/a
    */

    function __construct() {

        // vars
        $this->name = 'buckets';
        $this->label = __("Buckets Sidebar",'acf');
        $this->category = 'relational';
        $this->defaults = array(
            'post_type'         => array('buckets'),
            'max'               => 0,
            'filters'           => array('search'),
            'elements'          => array(),
            'return_format'     => 'ID'
        );
        $this->l10n = array(
            'max'       => __("Maximum values reached ( {max} values )",'acf'),
            'loading'   => __('Loading','acf'),
            'empty'     => __('No Buckets found','acf'),
        );


        // extra
        add_action('wp_ajax_acf/fields/relationship/query',         array($this, 'ajax_query'));
        add_action('wp_ajax_nopriv_acf/fields/relationship/query',  array($this, 'ajax_query'));


        // do not delete!
        parent::__construct();

    }


    /*
    *  ajax_query
    *
    *  description
    *
    *  @type    function
    *  @date    24/10/13
    *  @since   5.0.0
    *
    *  @param   $post_id (int)
    *  @return  $post_id (int)
    */

    function ajax_query() {

        // validate
        if( !acf_verify_ajax() ) die();


        // get choices
        $response = $this->get_ajax_query( $_POST );


        // return
        acf_send_ajax_results($response);

    }


    /*
    *  get_ajax_query
    *
    *  This function will return an array of data formatted for use in a select2 AJAX response
    *
    *  @type    function
    *  @date    15/10/2014
    *  @since   5.0.9
    *
    *  @param   $options (array)
    *  @return  (array)
    */

    function get_ajax_query( $options = array() ) {

        // defaults
        $options = acf_parse_args($options, array(
            'post_id'       => 0,
            's'             => '',
            'field_key'     => '',
            'paged'         => 1,
            'post_type'     => '',
            'taxonomy'      => ''
        ));


        // load field
        $field = acf_get_field( $options['field_key'] );
        if( !$field ) return false;


        // vars
        $results = array();
        $args = array();
        $s = false;
        $is_search = false;


        // paged
        $args['posts_per_page'] = 20;
        $args['paged'] = $options['paged'];


        // search
        if( $options['s'] !== '' ) {

            // strip slashes (search may be integer)
            $s = wp_unslash( strval($options['s']) );


            // update vars
            $args['s'] = $s;
            $is_search = true;

        }


        // post_type
        if( !empty($options['post_type']) ) {

            $args['post_type'] = acf_get_array( $options['post_type'] );

        } elseif( !empty($field['post_type']) ) {

            $args['post_type'] = acf_get_array( $field['post_type'] );

        } else {

            $args['post_type'] = acf_get_post_types();

        }


        // taxonomy
        if( !empty($options['taxonomy']) ) {

            // vars
            $term = acf_decode_taxonomy_term($options['taxonomy']);


            // tax query
            $args['tax_query'] = array();


            // append
            $args['tax_query'][] = array(
                'taxonomy'  => $term['taxonomy'],
                'field'     => 'slug',
                'terms'     => $term['term'],
            );


        } elseif( !empty($field['taxonomy']) ) {

            // vars
            $terms = acf_decode_taxonomy_terms( $field['taxonomy'] );


            // append to $args
            $args['tax_query'] = array();


            // now create the tax queries
            foreach( $terms as $k => $v ) {

                $args['tax_query'][] = array(
                    'taxonomy'  => $k,
                    'field'     => 'slug',
                    'terms'     => $v,
                );

            }

        }


        // filters
        $args = apply_filters('acf/fields/relationship/query', $args, $field, $options['post_id']);
        $args = apply_filters('acf/fields/relationship/query/name=' . $field['name'], $args, $field, $options['post_id'] );
        $args = apply_filters('acf/fields/relationship/query/key=' . $field['key'], $args, $field, $options['post_id'] );


        // get posts grouped by post type
        $groups = acf_get_grouped_posts( $args );


        // bail early if no posts
        if( empty($groups) ) return false;


        // loop
        foreach( array_keys($groups) as $group_title ) {

            // vars
            $posts = acf_extract_var( $groups, $group_title );


            // data
            $data = array(
                'text'      => $group_title,
                'children'  => array()
            );


            // convert post objects to post titles
            foreach( array_keys($posts) as $post_id ) {

                $posts[ $post_id ] = $this->get_post_title( $posts[ $post_id ], $field, $options['post_id'] );

            }


            // order posts by search
            if( $is_search && empty($args['orderby']) ) {

                $posts = acf_order_by_search( $posts, $args['s'] );

            }


            // append to $data
            foreach( array_keys($posts) as $post_id ) {

                $data['children'][] = $this->get_post_result( $post_id, $posts[ $post_id ]);

            }


            // append to $results
            $results[] = $data;

        }


        // add as optgroup or results
        if( count($args['post_type']) == 1 ) {

            $results = $results[0]['children'];

        }


        // vars
        $response = array(
            'results'   => $results,
            'limit'     => $args['posts_per_page']
        );


        // return
        return $response;

    }


    /*
    *  get_post_result
    *
    *  This function will return an array containing id, text and maybe description data
    *
    *  @type    function
    *  @date    7/07/2016
    *  @since   5.4.0
    *
    *  @param   $id (mixed)
    *  @param   $text (string)
    *  @return  (array)
    */

    function get_post_result( $id, $text ) {

        // vars
        $result = array(
            'id'    => $id,
            'text'  => $text
        );


        // return
        return $result;

    }




   /*
    *  get_post_title
    *
    *  This function returns the HTML for a result
    *
    *  @type    function
    *  @date    1/11/2013
    *  @since   5.0.0
    *
    *  @param   $post (object)
    *  @param   $field (array)
    *  @param   $post_id (int) the post_id to which this value is saved to
    *  @return  (string)
    */

    function get_post_title( $post, $field, $post_id = 0, $is_search = 0 ) {

        // get post_id
        if( !$post_id ) $post_id = acf_get_form_data('post_id');


        // vars
        $title = acf_get_post_title( $post, $is_search );


        // featured_image
        if( acf_in_array('featured_image', $field['elements']) ) {

            // vars
            $class = 'thumbnail';
            $thumbnail = acf_get_post_thumbnail($post->ID, array(17, 17));


            // icon
            if( $thumbnail['type'] == 'icon' ) {

                $class .= ' -' . $thumbnail['type'];

            }


            // append
            $title = '<div class="' . $class . '">' . $thumbnail['html'] . '</div>' . $title;

        }


        // filters
        $title = apply_filters('acf/fields/relationship/result', $title, $post, $field, $post_id);
        $title = apply_filters('acf/fields/relationship/result/name=' . $field['_name'], $title, $post, $field, $post_id);
        $title = apply_filters('acf/fields/relationship/result/key=' . $field['key'], $title, $post, $field, $post_id);


        // return
        return $title;

    }



    /*
    *  render_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param   $field - an array holding all the field's data
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    */

    function render_field( $field ) {

        // vars
        $values = array();
        $atts = array(
            'id'                => $field['id'],
            'class'             => "acf-relationship acf-buckets {$field['class']}",
            'data-max'          => $field['max'],
            'data-s'            => '',
            'data-post_type'    => '',
            'data-taxonomy'     => '',
            'data-paged'        => 1,
        );


        // Lang
        if( defined('ICL_LANGUAGE_CODE') ) {

            $atts['data-lang'] = ICL_LANGUAGE_CODE;

        }


        // data types
        $field['post_type'] = acf_get_array( $field['post_type'] );

        // post_types
        $post_types = array();

        if( !empty($field['post_type']) ) {

            $post_types = $field['post_type'];


        } else {

            $post_types = acf_get_post_types();

        }

        $post_types = acf_get_pretty_post_types($post_types);


        ?>
<div <?php acf_esc_attr_e($atts); ?>>

    <div class="acf-hidden">
        <input type="hidden" name="<?php echo $field['name']; ?>" value="" />
    </div>

    <div class="filters">
        <ul class="acf-hl">
            <li style="width:50%;">
                <div class="inner">
                <input class="filter" data-filter="s" placeholder="<?php _e("Search...",'acf'); ?>" type="text" />
                </div>
            </li>
            <li style="width:50%;">
                <a href="<?php echo bloginfo('url'); ?>/wp-content/plugins/buckets/admin/add_bucket.php?TB_iframe=1" title="Add New Bucket" class="button button-primary new-bucket-button thickbox">Add New Bucket</a>
            </li>
        </ul>
    </div>

    <div class="selection acf-cf">

        <div class="choices">

            <ul class="acf-bl list"></ul>

        </div>

        <div class="values">

            <ul class="acf-bl list">

                <?php if( !empty($field['value']) ):

                   // get posts
                    $posts = acf_get_posts(array(
                        'post__in' => $field['value'],
                    ));

                    // set choices
                    if( !empty($posts) ):

                        foreach( array_keys($posts) as $i ):

                            // vars
                            $post = acf_extract_var( $posts, $i );


?><li>
    <input type="hidden" name="<?php echo $field['name']; ?>[]" value="<?php echo $post->ID; ?>" />
    <span data-id="<?php echo $post->ID; ?>" class="acf-rel-item">
        <?php echo $this->get_post_title( $post, $field ); ?>
        <a href="#" class="acf-icon small dark" data-name="remove_item"><span class="dashicons dashicons-minus"></span></i></a>
    </span>
</li><?php

                        endforeach;

                    endif;

                endif; ?>

            </ul>

        </div>

    </div>

</div>
        <?php
    }



    /*
    *  render_field_settings()
    *
    *  Create extra options for your field. This is rendered when editing a field.
    *  The value of $field['name'] can be used (like bellow) to save extra data to the $field
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $field  - an array holding all the field's data
    */

    function render_field_settings( $field ) {

        // max
        $field['max'] = empty($field['max']) ? '' : $field['max'];



        acf_render_field_setting( $field, array(
            'label'         => __('Maximum posts','acf'),
            'instructions'  => '',
            'type'          => 'number',
            'name'          => 'max',
        ));


        // return_format
        acf_render_field_setting( $field, array(
            'label'         => __('Return Format','acf'),
            'instructions'  => '',
            'type'          => 'radio',
            'name'          => 'return_format',
            'choices'       => array(
                'object'        => __("Post Object",'acf'),
                'id'            => __("Post ID",'acf'),
            ),
            'layout'    =>  'horizontal',
        ));


    }


    /*
    *  format_value()
    *
    *  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
    *
    *  @type    filter
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $value (mixed) the value which was loaded from the database
    *  @param   $post_id (mixed) the $post_id from which the value was loaded
    *  @param   $field (array) the field array holding all the field options
    *
    *  @return  $value (mixed) the modified value
    */

    function format_value( $value, $post_id, $field ) {

        // bail early if no value
        if( empty($value) ) {

            return $value;

        }

        // force value to array
        $value = acf_get_array( $value );

        // convert to int
        $value = array_map('intval', $value);

        $buckets = false;
        foreach ($value as $v){
            $buckets .= get_bucket($v);
        }

        // return
        return $buckets;

    }




    /*
    *  update_value()
    *
    *  This filter is appied to the $value before it is updated in the db
    *
    *  @type    filter
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $value - the value which will be saved in the database
    *  @param   $post_id - the $post_id of which the value will be saved
    *  @param   $field - the field array holding all the field options
    *
    *  @return  $value - the modified value
    */

    function update_value( $value, $post_id, $field ) {

        // validate
        if( empty($value) ) {

            return $value;

        }


        // force value to array
        $value = acf_get_array( $value );


        // array
        foreach( $value as $k => $v ){

            // object?
            if( is_object($v) && isset($v->ID) ) {

                $value[ $k ] = $v->ID;

            }

        }


        // save value as strings, so we can clearly search for them in SQL LIKE statements
        $value = array_map('strval', $value);


        // return
        return $value;

    }

}

new acf_field_buckets();

endif;

?>