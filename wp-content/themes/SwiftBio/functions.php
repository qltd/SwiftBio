<?php


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
add_image_size( 'square_thumb', 600, 600, true );


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
function create_post_type() {

    register_post_type( 'projects',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Projects' ),
                'singular_name' => __( 'Project' ),
                'add_new_item' => __('Add New Project'),
                'not_found' => __( 'No Projects Found'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'projects'),
        )
    );

    $labels = array(
        'name' => _x( 'Clients', 'taxonomy general name' ),
        'singular_name' => _x( 'Client', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Clients' ),
        'all_items' => __( 'All Clients' ),
        'edit_item' => __( 'Edit Client' ),
        'update_item' => __( 'Update Client' ),
        'add_new_item' => __( 'Add New Client' ),
        'not_found' => __('No Clients Found'),
        'menu_name' => __( 'Clients' ),
    );

    // Now register the taxonomy
    register_taxonomy('clients',array('projects'), array(
        'public' => true,
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'description' => false,
        'rewrite' => array( 'slug' => 'clients'),
    ));
}

// Hooking up our function to theme setup
add_action( 'init', 'create_post_type' );

/*
* Gets the ID of the client from the option_name
 */
function getClientID($opt){
    return str_replace('clients_', '', str_replace('_services_section', '', $opt));
}

/*
* Returns a list of clients (terms) based on ID's pertaining to the section (Design, Branding or Dev)
 */
function getClients($section){
    global $wpdb;
    $IDs = false;
    $var = '%' . $section . '%';
    $results = $wpdb->get_results($wpdb->prepare("SELECT option_name FROM $wpdb->options WHERE option_value LIKE %s AND option_name LIKE %s", $var, '%services_section%'), ARRAY_A );
    foreach ($results as $o){
        $IDs .= getClientID($o['option_name']) . ',';
    }

    $IDs = rtrim($IDs, ",");

    $clients = get_terms( array(
        'taxonomy' => 'clients',
        'hide_empty' => true,
        'include' => $IDs,
    ));

    return $clients;
}

function formatServices($services){
    $return = false;
    if (in_array('Branding', $services)){
        $return .= '<span class="branding">Branding</span>';
        if (count($services) >= 3){
            $return .= ', ';
        }
    }

    if (in_array('Design', $services)){
        if (count($services) >= 2 && !in_array('Development', $services)){
            $return .= ' & ';
        }
        $return .= '<span class="design">Design</span>';
    }

    if (in_array('Development', $services)){
        if (count($services) >= 2){
            $return .= ' & ';
        }
        $return .= '<span class="development">Development</span>';
    }

    return $return;
}