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
        'label' => 'Testomonials',
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

add_action('init', 'testimonials_cpt');

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
