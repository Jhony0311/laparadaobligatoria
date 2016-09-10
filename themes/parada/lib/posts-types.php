<?php

namespace Roots\Sage\PostTypes;

/**
 * Register post types
 */

function testimonials_cpt() {
    $args = array(
        'label' => 'Testomonials',
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-format-status',
        'show_in_rest' => true,
    );

    register_post_type('testimonials', $args);
}

add_action('init', 'testimonials_cpt');
