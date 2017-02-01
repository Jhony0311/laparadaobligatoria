<?php
defined('ABSPATH') or die(':)');

abstract class ingalleryControllerFrontend
{

	static function renderShortcode($atts){
		$attribs = shortcode_atts(array('id'=>0),$atts);
		$id = (int)$attribs['id'];
		$page = 1;
		try{
			$gallery = new ingalleryModel();
			if(!$gallery->load($id)){
				throw new Exception($gallery->getError());
			}
			$view = new ingalleryView();
			$view->set('albums',$gallery->get('albums'));
			$view->set('cfg',$gallery->get('cfg'));
			$view->set('page',$page);
			$view->set('items',$gallery->getItemsPage($page));
			$view->set('has_more',$gallery->hasItemsPage($page+1));
			$view->set('id',$gallery->getStored('id'));
			
			$view->setTemplate('gallery');
			return $view->render();
		}
		catch( Exception $e){
			$html = '<div class="ingallery-message ing-error"><div class="ingallery-message-title">'.__('Unfortunately, an error occurred','ingallery').'</div><div class="ingallery-message-text">'.$e->getMessage().'</div></div>';
			return $html;
		}
	}
	
	static function scriptsAndStyles(){
		wp_enqueue_style('google-font-opensans');
		wp_enqueue_style('ingallery-icon-font');
		wp_enqueue_style('ingallery-frontend-styles');
		wp_enqueue_style('ingallery-slick-styles');
		wp_enqueue_script('jq-form');
		wp_enqueue_script('ingallery-slick');
		wp_enqueue_script('ingallery-plugin');
		wp_localize_script('ingallery-plugin', 'ingallery_ajax_object',array( 
			'ajax_url' => admin_url( 'admin-ajax.php'),
			'lang' => array(
				'error_title' => __('Unfortunately, an error occurred','ingallery'),
				'system_error' => __('Sytem error. Please refresh the page and try again','ingallery'),
			)
		));
	}
		
}