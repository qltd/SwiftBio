<?php

/* Declare Woocommere Support */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// replace the add to cart shortcode to show quantity
//add_filter( 'woocommerce_loop_add_to_cart_link', 'quantity_inputs_for_woocommerce_loop_add_to_cart_link', 10, 2 );

function quantity_inputs_for_woocommerce_loop_add_to_cart_link( $html, $product ) {
    if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
        $html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
        $html .= woocommerce_quantity_input( array(), $product, false );
        $html .= '<button type="submit" class="button product_type_simple add_to_cart_button ajax_add_to_cart">' . esc_html( $product->add_to_cart_text() ) . '</button>';
        $html .= '</form>';
    }
    return $html;
}


function detectLocation(){
    $geoip = geoip_detect2_get_info_from_current_ip();
    $country = $geoip->raw[ 'country' ][ 'iso_code' ];
    if ( 'US' === $country ) {
        return true;
    }

    return false;
}



/**
 * _q functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _q
 */

//Additional Media Library Sizes
add_image_size( 'product_tagline', 280, 200, true );
add_image_size( 'featured_product', 280, 310, true );
add_image_size( 'sidebar_thumb', 470, 0, true );



if ( ! function_exists( '_q_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function _q_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on _q, use a find and replace
	 * to change '_q' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( '_q', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', '_q' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( '_q_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', '_q_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _q_content_width() {
	$GLOBALS['content_width'] = apply_filters( '_q_content_width', 640 );
}
add_action( 'after_setup_theme', '_q_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _q_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', '_q' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', '_q_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function _q_scripts() {
	wp_enqueue_style( '_s-style', get_stylesheet_uri() );

	wp_enqueue_script( '_s-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( '_s-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', '_q_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



//Page Slug Body Class
function add_slug_body_class( $classes ) {
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );



// Our custom post type function
function create_post_types() {

    register_post_type( 'events',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Events' ),
                'singular_name' => __( 'Event' ),
                'add_new_item' => __('Add New Event'),
                'not_found' => __( 'No Events Found'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'events'),
        )
    );

    register_post_type( 'careers',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Careers' ),
                'singular_name' => __( 'Career' ),
                'add_new_item' => __('Add New Career'),
                'not_found' => __( 'No Careers Found'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'careers'),
        )
    );

}

// Hooking up our function to theme setup
add_action( 'init', 'create_post_types' );





add_filter('acf/location/rule_types', 'acf_location_rules_page_grandparent');
function acf_location_rules_page_grandparent($choices) {
    $choices['Page']['page_grandparent'] = 'Page Grandparent';
    return $choices;
}

add_filter('acf/location/rule_values/page_grandparent','acf_location_rules_values_page_grandparent');
function acf_location_rules_values_page_grandparent($choices) {
  // this code is copied directly from
  // render_location_values()
  // case "page"
  $groups = acf_get_grouped_posts(array(
    'post_type' => 'page'
  ));
  if (!empty($groups)) {
    foreach(array_keys($groups) as $group_title) {
      $posts = acf_extract_var($groups, $group_title);
      foreach(array_keys($posts) as $post_id) {
        $posts[$post_id] = acf_get_post_title($posts[$post_id]);
      };
      $choices = $posts;
    }
  }
  // end of copy from ACF
  return $choices;
}

add_filter('acf/location/rule_match/page_grandparent', 'acf_location_rules_match_page_grandparent', 10, 3);
function acf_location_rules_match_page_grandparent($match, $rule, $options) {
  // this code is with inspiration from
  // acf_location::rule_match_page_parent()
  // with addition of adding grandparent check
  if(!$options['post_id']) {
    return false;
  }
  $post_grandparent = 0;
  $post = get_post($options['post_id']);
  if ($post->post_parent) {
    $parent = get_post($post->post_parent);
    if ($parent->post_parent) {
      $post_grandparent = $parent->post_parent;
    }
  }
  if (isset($options['page_parent']) && $options['page_parent']) {
    $parent = get_post($options['page_parent']);
    if ($parent->post_parent) {
      $post_grandparent = $parent->post_parent;
    }
  }
  if (!$post_grandparent) {
    return false;
  }
  if ($rule['operator'] == "==") {
    $match = ($post_grandparent == $rule['value']);
  } elseif ($rule['operator'] == "!=") {
    $match = ($post_grandparent != $rule['value']);
  }
  return $match;
}


/*
   Debug preview with custom fields
   Taken from: http://support.advancedcustomfields.com/forums/topic/preview-solution/
   See also: http://support.advancedcustomfields.com/forums/topic/2nd-every-other-post-preview-throws-notice/
*/
add_filter('_wp_post_revision_fields', 'add_field_debug_preview');
function add_field_debug_preview($fields){
   $fields["debug_preview"] = "debug_preview";
   return $fields;
}
add_action( 'edit_form_after_title', 'add_input_debug_preview' );
function add_input_debug_preview() {
   echo '<input type="hidden" name="debug_preview" value="debug_preview">';
}


/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function wpdocs_excerpt_more( $more ) {
    return ' <br /><a href="' . get_the_permalink() . '">More &raquo;</a>';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );



/* Get the title of the Archive page template */
function get_archive_title(){
    if (get_the_archive_title() != 'Archives') {
        $title = str_replace('Archives: ', '', get_the_archive_title());
    } elseif (is_search()) {
        $title = 'Search Results';
    } elseif (is_single()){
        $post_type_obj = get_post_type_object( get_post_type() );
        $title = apply_filters('post_type_archive_title', $post_type_obj->labels->name );
        if ($title == 'Posts') { $title = 'News'; }
    } else {
        $title = 'News';
    }
    return $title;
}



/* Add ACF Options Page */
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page();

}



// functions.php
add_action( 'init', 'update_my_custom_type', 99 );
/**
 * update_my_custom_type
 *
 * @author  Joe Sexton <joe@webtipblog.com>
 */
function update_my_custom_type() {
    global $wp_post_types;

    if ( post_type_exists( 'product' ) ) {

        // exclude from search results
        $wp_post_types['product']->exclude_from_search = true;
    }
}


/* Pagination Bar */
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {
         //echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}


/* Opan Graph */
function doctype_opengraph($output) {
    return $output . '
    xmlns:og="http://opengraphprotocol.org/schema/"
    xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'doctype_opengraph');

function fb_opengraph() {
    global $post;

    if(has_post_thumbnail($post->ID)) {
        $img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'medium');
    } else {
        $img_src = get_stylesheet_directory_uri() . '/img/opengraph_image.jpg';
    }

    if($post->post_excerpt != '') {
        $excerpt = strip_tags($post->post_excerpt);
        $excerpt = str_replace("", "'", $excerpt);
    } elseif ($post->post_content != ''){
        $excerpt = strip_tags($post->post_content);
        $excerpt = str_replace("", "'", $excerpt);
        $excerpt = wp_trim_words($excerpt, 25);
    } else {
        $excerpt = get_bloginfo('description');
    }
    ?>

    <meta property="og:title" content="<?php echo get_the_title(); ?>"/>
    <meta property="og:description" content="<?php echo $excerpt; ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="<?php echo get_permalink(); ?>"/>
    <meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
    <meta property="og:image" content="<?php echo $img_src; ?>"/>

<?php
}
add_action('wp_head', 'fb_opengraph', 5);


/* Add Subscript buttons in Wordpress */
function my_mce_buttons_2($buttons) {
    /*** Add in a core button that's disabled by default*/
    $buttons[] = 'sup';
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';
    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');


/* Set Images to not link by default */
function wpb_imagelink_setup() {
    $image_set = get_option( 'image_default_link_type' );

    if ($image_set !== 'none') {
        update_option('image_default_link_type', 'none');
    }
}
add_action('admin_init', 'wpb_imagelink_setup', 10);





/**
 * Add the field to the checkout
 **/
add_action('woocommerce_after_order_notes', 'my_custom_checkout_field');

function my_custom_checkout_field( $checkout ) {

    echo '<div id="po_number">';

    /**
     * Output the field. This is for 1.4.
     *
     * To make it compatible with 1.3 use $checkout->checkout_form_field instead:

     $checkout->checkout_form_field( 'my_field_name', array(
        'type'          => 'text',
        'class'         => array('my-field-class orm-row-wide'),
        'label'         => __('Fill in this field'),
        'placeholder'   => __('Enter a number'),
        ));
     **/
    woocommerce_form_field( 'po_number', array(
        'type'          => 'text',
        'class'         => array('my-field-class orm-row-wide'),
        'label'         => __('PO Number'),
        'placeholder'   => __(''),
        ), $checkout->get_value( 'po_number' ));

    echo '</div>';

}



/**
 * Update the user meta with field value
 **/
add_action('woocommerce_checkout_update_user_meta', 'my_custom_checkout_field_update_user_meta');

function my_custom_checkout_field_update_user_meta( $user_id ) {
    if ($user_id && $_POST['po_number']) update_user_meta( $user_id, 'po_number', esc_attr($_POST['po_number']) );
}

/**
 * Update the order meta with field value
 **/
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ($_POST['po_number']) update_post_meta( $order_id, 'P.O. Number', esc_attr($_POST['po_number']));
}

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'my_custom_checkout_field_order_meta_keys');

function my_custom_checkout_field_order_meta_keys( $keys ) {
    $keys[] = 'P.O. Number';
    return $keys;
}