<?php
/*
Plugin Name: Beautiful Gallery
Plugin URI: https://simplybeautifulprint.co.uk
Description: Custom Gallery Plugin
Version: 1.0
Author: Bradley Moon
Author URI: http://bradleymoon.co.uk
*/




add_action('init', 'my_init_function');
function my_init_function() {
    if (function_exists('acf_add_options_page')) {
        $page = acf_add_options_page(array(
            'menu_title' => 'Beautiful Gallery Settings',
            'menu_slug' => 'beautiful-gallery-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        ));
    }
}


function twentytwelve_add_lightbox() {
    

    //Colorbox stylesheet
       wp_enqueue_style( 'colorbox', plugin_dir_url( __FILE__ ) . 
       'includes/colourbox/colorbox.css' ); 

       wp_enqueue_style( 'beautifulgallery', plugin_dir_url( __FILE__ ) . 
       'includes/css/beautiful-gallery.css' );

       wp_enqueue_style( 'font-awesome', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css"' );
     
       //Colorbox jQuery plugin js file
      wp_enqueue_script( 'colorbox', plugin_dir_url( __FILE__ ) . 
      'includes/colourbox/jquery.colorbox-min.js', array( 'jquery'   ), '', true );

      //Make the Colorbox text translation-ready
    $current = 'current';
    $total = 'total';
    wp_localize_script( 'colorbox', 'themeslug_script_vars', array(
        'current'   => sprintf(__( 'image {%1$s} of {%2$s}', 'themeslug'), $current, $total ),
        'previous'  =>  __( 'previous', 'themeslug' ),
        'next'      =>  __( 'next', 'themeslug' ),
        'close'     =>  __( 'close', 'themeslug' ),
        'xhrError'  =>  __( 'This content failed to load.', 'themeslug' ),
        'imgError'  =>  __( 'This image failed to load.', 'themeslug' )
      ) 
    );

    //Add main.js file
      wp_enqueue_script( 'themeslug-script', plugin_dir_url( __FILE__ ) . 
      'includes/js/main.js', array( 'colorbox' ), '', true );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_add_lightbox' );





function wcpt_locate_template( $template_name, $template_path = '', $default_path = '' ) {
    // Set variable to search in woocommerce-plugin-templates folder of theme.
    if ( ! $template_path ) :
        $template_path = 'includes/';
    endif;
    // Set default plugin templates path.
    if ( ! $default_path ) :
        $default_path = plugin_dir_path( __FILE__ ) . 'includes/'; // Path to the template folder
    endif;
    // Search template file in theme folder.
    $template = locate_template( array(
        $template_path . $template_name,
        $template_name
    ) );
    // Get plugins template file.
    if ( ! $template ) :
        $template = $default_path . $template_name;
    endif;
    return apply_filters( 'wcpt_locate_template', $template, $template_name, $template_path, $default_path );
}


function wcpt_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
    if ( is_array( $args ) && isset( $args ) ) :
        extract( $args );
    endif;
    $template_file = wcpt_locate_template( $template_name, $tempate_path, $default_path );
    if ( ! file_exists( $template_file ) ) :
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
        return;
    endif;
    include $template_file;
}



if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Start: Detect ACF Pro plugin. Include if not present. */
if ( !class_exists('acf') ) { // if ACF Pro plugin does not currently exist
  /** Start: Customize ACF path */
  add_filter('acf/settings/path', 'cysp_acf_settings_path');
  function cysp_acf_settings_path( $path ) {
    $path = plugin_dir_path( __FILE__ ) . 'includes/acf/';
    return $path;
  }
  /** End: Customize ACF path */

  /** Start: Customize ACF dir */
  add_filter('acf/settings/dir', 'cysp_acf_settings_dir');
  function cysp_acf_settings_dir( $path ) {
    $dir = plugin_dir_url( __FILE__ ) . 'includes/acf/';
    return $dir;
  }
  /** End: Customize ACF path */

  /** Start: Include ACF */
  include_once( plugin_dir_path( __FILE__ ) . 'includes/acf/acf.php' );
  /** End: Include ACF */

  /** Start: Hide ACF field group menu item */
  /// add_filter('acf/settings/show_admin', '__return_false');
  /** End: Hide ACF field group menu item */

  /** Start: Create JSON save point */
  add_filter('acf/settings/save_json', 'cysp_acf_json_save_point');
  function cysp_acf_json_save_point( $path ) {
    $path = plugin_dir_path( __FILE__ ) . 'acf-json/';
    return $path;
  }
  /** End: Create JSON save point */

  /** Start: Create JSON load point */
  add_filter('acf/settings/load_json', 'cysp_acf_json_load_point');
  /** End: Create JSON load point */

  /** Start: Stop ACF upgrade notifications */
  add_filter( 'site_transient_update_plugins', 'cysp_stop_acf_update_notifications', 11 );
  function cysp_stop_acf_update_notifications( $value ) {
    unset( $value->response[ plugin_dir_path( __FILE__ ) . 'acf/acf.php' ] );
    return $value;
  }
  /** End: Stop ACF upgrade notifications */

} else { // else ACF Pro plugin does exist
  /** Start: Create JSON load point */
  add_filter('acf/settings/load_json', 'cysp_acf_json_load_point');
  /** End: Create JSON load point */
} // end-if ACF Pro plugin does not currently exist
/** End: Detect ACF Pro plugin. Include if not present. */
/** Start: Function to create JSON load point */
function cysp_acf_json_load_point( $paths ) {
  $paths[] = plugin_dir_path( __FILE__ ) . 'acf-json';
  return $paths;
}
/** End: Function to create JSON load point */

// register custom post type to work with
function wpmudev_create_post_type() {
	// set up labels
	$labels = array(
 		'name' => 'Galleries',
    	'singular_name' => 'Gallery',
    	'add_new' => 'Add New Gallery',
    	'add_new_item' => 'Add New Gallery',
    	'edit_item' => 'Edit Gallery',
    	'new_item' => 'New Gallery',
    	'all_items' => 'All Galleries',
    	'view_item' => 'View Gallery',
    	'search_items' => 'Search Galleries',
    	'not_found' =>  'No Galleries Found',
    	'not_found_in_trash' => 'No Galleries found in Trash', 
    	'parent_item_colon' => '',
    	'menu_name' => 'Galleries',
    );
    //register post type
	register_post_type( 'gallery', array(
		'labels' => $labels,
		'has_archive' => true,
 		'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-images-alt2',
		'supports' => array( 'title', 'excerpt', 'custom-fields', 'thumbnail','page-attributes' ),	
		'exclude_from_search' => true,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'gallery' ),
		)
	);
}
add_action( 'init', 'wpmudev_create_post_type' );




add_shortcode('beautifulgallery', 'custom_gallery_shortcode_query');

function custom_gallery_shortcode_query( $atts ){
$output = '';
 
    // define attributes and their defaults
    extract( shortcode_atts( array (
        'type' => 'gallery',
        'posts' => -1,
        'id' => '',

    ), $atts ) );
 
    // define query parameters based on attributes
    $options = array(
        'post_type' => $type,
        'posts_per_page' => $posts,
        'p' => $id,
    );
    $query = new WP_Query( $options );
   if($query->have_posts()) : 

        while ( $query->have_posts() ) : $query->the_post(); 
        ob_start();
        echo wcpt_get_template( 'gallery-layout.php' );
        $output .= ob_get_clean();

        endwhile; //end the while loop
        wp_reset_query();


endif; // end of the loop.  

return $output;
    

}




add_action( 'add_meta_boxes', 'cd_meta_box_add' );



function cd_meta_box_add()
{
    add_meta_box( 'my-gallery-shortcode', 'Gallery Shortcode', 'cd_meta_box_cb', 'gallery', 'normal', 'high' );
}
    


function cd_meta_box_cb()
{
    
global $post;
$formstart = '[beautifulgallery id="';
$formend = '"][/beautifulgallery]';
$value = $formstart . $post->ID . $formend;

echo $value;
 

}


add_action('acf/init', 'my_acf_init');
function my_acf_init() {
  
  // check function exists
  if( function_exists('acf_register_block') ) {
    
    // register a testimonial block
    acf_register_block(array(
      'name'        => 'testimonial',
      'title'       => __('Testimonial'),
      'description'   => __('A custom testimonial block.'),
      'render_callback' => 'my_acf_block_render_callback',
      'category'      => 'formatting',
      'icon'        => 'admin-comments',
      'keywords'      => array( 'testimonial', 'quote' ),
    ));
  }
}




?>