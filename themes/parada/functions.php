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
    $labels = array(
        'name'               => 'Productos',
        'singular_name'      => 'Producto',
        'menu_name'          => 'Productos',
        'name_admin_bar'     => 'Producto',
        'add_new'            => 'Añadir nuevo',
        'add_new_item'       => 'Añadir producto nuevo',
        'new_item'           => 'Producto nuevo',
        'edit_item'          => 'Editar producto',
        'view_item'          => 'Ver producto',
        'all_items'          => 'Todos los productos',
        'search_items'       => 'Buscar productos',
        'parent_item_colon'  => 'Producto padre:',
        'not_found'          => 'No se encontraron productos',
        'not_found_in_trash' => 'No se encontraron productos en la papelera'
    );
    $args = array(
        'labels' => $labels,
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

/**
 * Register Taxonomies
 */

function create_product_taxonomy() {
    $labels = array(
        'name'              => 'Tipos de producto',
        'singular_name'     => 'Tipos de producto',
        'search_items'      => 'Buscar Tipos de productos',
        'all_items'         => 'Todos los tipos de producto',
        'parent_item'       => 'Tipo de producto padre',
        'parent_item_colon' => 'Tipo de producto padre:',
        'edit_item'         => 'Editar tipo de producto',
        'update_item'       => 'Actualizar tipo de producto',
        'add_new_item'      => 'Añadir tipo de producto',
        'new_item_name'     => 'Nuevo tipo de producto',
        'menu_name'         => 'Tipos de producto'
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tipo' ),
    );

    register_taxonomy( 'product_type', 'products', $args );
}

/**
 * Add to init call block
 */

add_action( 'init', 'create_product_taxonomy', 0 );
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

function my_acf_google_map_api( $api ){

    $api['key'] = 'AIzaSyClXtqGBvGe1XjWHGaUt42YjebQ0ZV9F9k';

    return $api;

}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

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
