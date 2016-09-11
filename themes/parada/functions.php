<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];
/**
 * Add logo support
 */
add_theme_support( 'custom-logo' );

/**
 * Register post types
 */

function testimonials_cpt() {
    $args = array(
        'label' => 'Testimonials',
        'public' => false,
        'show_ui' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-status',
        'supports' => array(
            'title',
            'editor'
        )
    );

    register_post_type('testimonials', $args);
}

function banners_cpt() {
    $args = array(
        'label' => 'Banners',
        'public' => false,
        'show_ui' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-grid-view',
        'supports' => array(
            'title',
            'editor'
        )
    );

    register_post_type('banners', $args);
}

function products_cpt() {
    $args = array(
        'label' => 'Productos',
        'public' => true,
        'show_ui' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-store',
        'supports' => array(
            'title',
            'editor'
        )
    );

    register_post_type('products', $args);
}

add_action('init', 'testimonials_cpt');
add_action('init', 'banners_cpt');
add_action('init', 'products_cpt');

/**
 * Add shortcodes
 */

function grid_shortcode( $atts, $content = null ) {
    return '<div class="row">' . do_shortcode($content) . '</div>';
}

function one_half_shortcode( $atts, $content = null ) {
    return '<div class="columns small-12 medium-6">' . do_shortcode($content) . '</div>';
}

function one_third_shortcode( $atts, $content = null ) {
    return '<div class="columns small-12 medium-4">' . do_shortcode($content) . '</div>';
}

function two_third_shortcode( $atts, $content = null ) {
    return '<div class="columns small-12 medium-8">' . do_shortcode($content) . '</div>';
}

add_shortcode( 'grid', 'grid_shortcode' );
add_shortcode( 'one_half', 'one_half_shortcode' );
add_shortcode( 'one_third', 'one_third_shortcode' );
add_shortcode( 'two_third', 'two_third_shortcode' );

function new_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Read More', 'your-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
