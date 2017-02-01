<?php
/*
Plugin Name: InGallery by Maxiolab
Plugin URI: http://ingallery.maxiolab.com
Description: Awesome Instagram gallery for wordpress
Author: Maxiolab
Version: 1.4
Author URI: https://codecanyon.net/user/maxiolab
*/

defined('ABSPATH') or die(':)');

define('INGALLERY_VERSION','1.4');
define('INGALLERY_DEBUG', false);
define('INGALLERY_FILE', __FILE__);
define('INGALLERY_PATH',plugin_dir_path( __FILE__ ));
define('INGALLERY_URL',plugin_dir_url(__FILE__));

require_once(INGALLERY_PATH.'include/input.php');
require_once(INGALLERY_PATH.'include/helper.php');
require_once(INGALLERY_PATH.'include/model.php');
require_once(INGALLERY_PATH.'include/view.php');
require_once(INGALLERY_PATH.'widgets/vc_widget_ingallery.php');

add_action('init', 'ingalleryHelper::onInit' );
add_action('widgets_init' , 'ingalleryHelper::widgetsInit', 77 );

if(is_admin()){
	require_once(INGALLERY_PATH.'controllers/backend.php');

	add_action('admin_print_styles', 'ingalleryControllerBackend::bind_admin_print_styles');
	add_action('admin_menu', 'ingalleryControllerBackend::bind_admin_menu', 7 );
	add_action('ingallery_admin_notices', 'ingalleryControllerBackend::bind_ingallery_admin_notices', 7 );
	add_action('wp_ajax_preview', 'ingalleryControllerBackend::ajax_gallery_preview');
	add_action('wp_ajax_save', 'ingalleryControllerBackend::ajax_gallery_save');
	add_action('wp_ajax_ingallery', 'ingalleryControllerBackend::handleFrontendGallery');
	add_action('wp_ajax_nopriv_ingallery', 'ingalleryControllerBackend::handleFrontendGallery');
	add_action('wp_ajax_comments', 'ingalleryControllerBackend::handleFrontendComments');
	add_action('wp_ajax_nopriv_comments', 'ingalleryControllerBackend::handleFrontendComments');
}
else{
	require_once(INGALLERY_PATH.'controllers/frontend.php');
	
	add_action('wp_enqueue_scripts', 'ingalleryControllerFrontend::scriptsAndStyles' );
	add_shortcode('ingallery', 'ingalleryControllerFrontend::renderShortcode');
}
