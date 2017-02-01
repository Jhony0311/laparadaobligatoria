<?php
defined('ABSPATH') or die(':)');

abstract class ingalleryControllerBackend
{
	
	static public $_notices = array(
		'notice' => array(),
		'success' => array(),
		'error' => array(),
	);

	static function bind_admin_menu(){
		global $_wp_last_object_menu;
		$_wp_last_object_menu++;
		$id = add_menu_page(__('InGallery', 'ingallery'), __('InGallery', 'ingallery'), 'manage_options', 'ingallery', 'ingalleryControllerBackend::ingallery_management_page', plugins_url('assets/images/ingallery-menu-icon.png', INGALLERY_FILE), $_wp_last_object_menu);
	}
	
	static function bind_admin_print_styles(){
		wp_enqueue_style('google-font-opensans');
		wp_enqueue_style('ingallery-icon-font');
		wp_enqueue_style('ingallery-tokenfield-styles');
		wp_enqueue_style('ingallery-colorpicker-styles');
		wp_enqueue_style('ingallery-slick-styles');
		wp_enqueue_style('ingallery-app-styles');
		wp_enqueue_style('ingallery-frontend-styles');
		wp_enqueue_style('ingallery-admin-styles');
		wp_enqueue_script('jq-form');
		wp_enqueue_script('ingallery-tokenfield');
		wp_enqueue_script('ingallery-colorpicker');
		wp_enqueue_script('ingallery-slick');
		wp_enqueue_script('ingallery-stepper');
		wp_enqueue_script('ingallery-plugin');
		wp_enqueue_script('ingallery-app');
		wp_enqueue_script('ingallery-admin-scripts');
		wp_localize_script('ingallery-plugin', 'ingallery_ajax_object',array( 
			'ajax_url' => admin_url( 'admin-ajax.php'),
			'lang' => array(
				'albums' => __('Albums','ingallery'),
				'layout' => __('Layout','ingallery'),
				'display' => __('Display','ingallery'),
				'colors' => __('Colors','ingallery'),
				'new_album' => __('New album','ingallery'),
				'album_name' => __('Album name','ingallery'),
				'sources' => __('Sources','ingallery'),
				'sources_help' => __('List of sources to get media from instagram. Can be any combination of @username, #hashtag or instagram photo URL separated by comma','ingallery'),
				'filters' => __('Filters','ingallery'),
				'filters_help' => __('List of filters to filter media from sources by various conditions. Can be any combination of @username, #hashtag, instagram photo URL, or "videos" keyword separated by comma. "videos" keyword means that you need to filter only videos. Use "videos" keyword without quotes, for example: #sexy, videos','ingallery'),
				'limit' => __('Limit items','ingallery'),
				'limit_help' => __('Limit media items in current album. Set to "0" to show all available media','ingallery'),
				'type' => __('Type','ingallery'),
				'cols' => __('Columns','ingallery'),
				'rows' => __('Rows','ingallery'),
				'gutter' => __('Gutter','ingallery'),
				'size_in_px' => __('Size in pixels','ingallery'),
				'style' => __('Style','ingallery'),
				'display_mode' => __('Display mode','ingallery'),
				'display_on_thumbs' => __('Display on thumbnails','ingallery'),
				'likes' => __('Likes counter','ingallery'),
				'comments' => __('Comments counter','ingallery'),
				'description' => __('Media description','ingallery'),
				'display_in_popup' => __('Display in popup window','ingallery'),
				'comments_list' => __('Comments','ingallery'),
				'username' => __('Username','ingallery'),
				'link_user_to_instagram' => __('Link to instagram profile on user block','ingallery'),
				'link_to_instagram' => __('link to instagram','ingallery'),
				'created_date' => __('Created date','ingallery'),
				'show_in_popup' => __('show in popup window','ingallery'),
				'add_filter' => __('Add filter','ingallery'),
				'except' => __('Except','ingallery'),
				'only' => __('Only','ingallery'),
				'add_album' => __('Add new album','ingallery'),
				'cache_lifetime' => __('Cache lifetime','ingallery'),
				'cache_lifetime_help' => __('Cache lifetime in seconds. Greater cache lifetime means less server load','ingallery'),
				'show_albums' => __('Show albums names','ingallery'),
				'show_loadmore' => __('Enable "Load more"','ingallery'),
				'loadmore_text' => __('Text on a "Load more" button','ingallery'),
				'video_plays' => __('Video plays counter','ingallery'),
				'system_error' => __('Sytem error. Please refresh the page and try again','ingallery'),
				'error_title' => __('Unfortunately, an error occurred','ingallery'),
				'congrats' => __('Congratulations!','ingallery'),
				'user_block' => __('Instagram user block','ingallery'),
				'popup_img_size' => __('Popup image size','ingallery'),
				'popup_img_size_help' => __('For smooth viewing experience choose "try to fit equal size". But if you want small images not to be stretched, choose "use image size".','ingallery'),
				'try_to_fit' => __('try to fit equal size','ingallery'),
				'full_size' => __('use image size','ingallery'),
				'loop_video' => __('Loop video','ingallery'),
				'album_btns_colors' => __('Album buttons colors','ingallery'),
				'gallery_bg' => __('Gallery background','ingallery'),
				'album_btn_bg' => __('Album button background','ingallery'),
				'album_btn_text' => __('Album button text color','ingallery'),
				'album_btn_hover_bg' => __('Album button background on mouse over','ingallery'),
				'album_btn_hover_text' => __('Album button text color on mouse over','ingallery'),
				'more_btn_bg' => __('"Load more" button background','ingallery'),
				'more_btn_text' => __('"Load more" button text color','ingallery'),
				'more_btn_hover_bg' => __('"Load more" button background on mouse over','ingallery'),
				'more_btn_hover_text' => __('"Load more" button text color on mouse over','ingallery'),
				'more_btn_colors' => __('"Load more" button colors','ingallery'),
				'thumbs_colors' => __('Thumbnails colors','ingallery'),
				'thumb_overlay_bg' => __('Thumbnails overlay background','ingallery'),
				'thumb_overlay_text' => __('Thumbnails overlay text color','ingallery'),
				'infinite_scroll' => __('Infinite scrolling','ingallery'),
				'' => __('','ingallery'),
			)
		));
	}
	
	static function addAdminNotice($msg,$type){
		self::$_notices[$type][] = $msg;
	}
	
	static function bind_ingallery_admin_notices(){
		$msgText = ingalleryInput::getString('msg_text','');
		$msgType = ingalleryInput::getCmd('msg_type','');
		if($msgText!='' && $msgType!=''){
			self::addAdminNotice($msgText,$msgType);
		}
		foreach(self::$_notices as $type=>$notices){
			foreach($notices as $notice){
				echo '<div id="message" class="notice notice-'.$type.' is-dismissible"><p>'.$notice.'</p></div>';
			}
		}
	}
	
	static function ingallery_management_page(){
		try{
			$action = ingalleryInput::getCmd('action','default');
			if(!in_array($action,array('default','edit','create'))){
				$action = 'default';
			}
			$view = new ingalleryView();
			if($action=='default'){
				require_once INGALLERY_PATH.'include/table.class.php';
				$current_screen = get_current_screen();
				add_filter('manage_'.$current_screen->id.'_columns', array('ingalleryTable', 'define_columns'));
				add_screen_option('per_page', array(
						'default' => 20,
						'option' => 'ingallery_galleries_per_page'
					) 
				);
				$tbl = new ingalleryTable();
				$view->set('tbl',$tbl);
				$view->setTemplate('admin/default');
			}
			else if($action=='edit' || $action=='create'){
				$id = ingalleryInput::getInt('id',0);
				$gallery = new ingalleryModel();
				if($id>0){
					if(!$gallery->load($id)){
						throw new Exception($gallery->getError());
					}
				}
				$view->set('gallery',$gallery);
				$view->setTemplate('admin/edit');
			}
			echo $view->render();
		}
		catch(Exception $e){
			trigger_error($e->getMessage());
		}
	}
	
	static function ajax_gallery_preview(){
		try{
			$page = ingalleryInput::getInt('page',1);
			$view = new ingalleryView();
			$gallery = new ingalleryModel();
			$gallery->bind(ingalleryInput::getVar('gallery'));
			if(!$gallery->check()){
				throw new Exception($gallery->getError());
			}

			$view->set('albums',$gallery->get('albums'));
			$view->set('cfg',$gallery->get('cfg'));
			$view->set('page',$page);
			$view->set('items',$gallery->getItemsPage($page));
			$view->set('has_more',$gallery->hasItemsPage($page+1));
			$view->set('id',rand(99,9999));
			
			$view->setTemplate('gallery');
			
			$responce = array(
				'status' => 'success',
				'has_more' => $gallery->hasItemsPage($page+1),
				'html' => $view->render()
			);
		}
		catch(Exception $e){
			$responce = array(
				'status' => 'error',
				'message' => $e->getMessage()
			);
		}
		die(json_encode($responce));
	}

	static function ajax_gallery_save(){
		try{
			$view = new ingalleryView();
			$gallery = new ingalleryModel();
			$gallery->bind(ingalleryInput::getVar('gallery'));
			if(!$gallery->check() || !$gallery->save()){
				throw new Exception($gallery->getError());
			}
			$responce = array(
				'status' => 'success',
				'id' => $gallery->getStored('id'),
				'message' => __('Gallery successfully saved','ingallery')
			);
		}
		catch(Exception $e){
			$responce = array(
				'status' => 'error',
				'message' => $e->getMessage()
			);
		}
		echo json_encode($responce);
		die();
		
	}
	
	static function handleFrontendComments(){
		try{
			$mediaCode = ingalleryInput::getVar('media_code','');
			if(!preg_match('~^'.ingalleryHelper::MATCH_IG_MEDIA_CODE_CLASS.'$~',$mediaCode)){
				throw new Exception(__('Media code is not set','ingallery'));
			}
			$gallery = new ingalleryModel();
			$items = ingalleryHelper::getMediaListFromSource('https://www.instagram.com/p/'.$mediaCode.'/',$gallery->getAlbumDefaults());
			if(!is_array($items) || !isset($items[0])){
				throw new Exception(__('No media found','ingallery'));
			}
			if(!isset($items[0]['comments']) || !isset($items[0]['comments']['nodes']) || !is_array($items[0]['comments']['nodes']) || count($items[0]['comments']['nodes'])==0){
				throw new Exception(__('No comments found','ingallery'));
			}
			$view = new ingalleryView();

			$view->set('comments',$items[0]['comments']['nodes']);			
			$view->setTemplate('popup-comments');
			
			$responce = array(
				'status' => 'success',
				'media_code' => $mediaCode,
				'html' => $view->render()
			);
		}
		catch(Exception $e){
			$responce = array(
				'status' => 'error',
				'message' => $e->getMessage()
			);
		}
		die(json_encode($responce));
	}

	static function handleFrontendGallery(){
		try{
			$id = ingalleryInput::getInt('id',0);
			$page = ingalleryInput::getInt('page',1);
			if($id<=0){
				throw new Exception(__('Gallery ID is not set','ingallery'));
			}
			$gallery = new ingalleryModel();
			if(!$gallery->load($id)){
				throw new Exception($gallery->getError());
			}
			if(!$gallery->get('cfg/layout_show_loadmore') && !$gallery->get('cfg/layout_infinite_scroll') && $pageNum>1){
				throw new Exception(':(');
			}
			$view = new ingalleryView();

			$view->set('albums',$gallery->get('albums'));
			$view->set('cfg',$gallery->get('cfg'));
			$view->set('page',$page);
			$view->set('items',$gallery->getItemsPage($page));
			$view->set('has_more',$gallery->hasItemsPage($page+1));
			$view->set('id',$gallery->getStored('id'));
			
			$view->setTemplate('gallery');
			
			$responce = array(
				'status' => 'success',
				'has_more' => $gallery->hasItemsPage($page+1),
				'html' => $view->render()
			);
		}
		catch(Exception $e){
			$responce = array(
				'status' => 'error',
				'message' => $e->getMessage()
			);
		}
		die(json_encode($responce));
	}
	
}