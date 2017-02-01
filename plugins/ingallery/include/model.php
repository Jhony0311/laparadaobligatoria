<?php
defined('ABSPATH') or die(':)');

class ingalleryModel
{
	const ING_WP_POST_TYPE = 'maxiolab_ingallery';
	private $_stored = array();
	private $_data = array(
			'albums' => array(
				array(
					'id' => 'someid',
					'name' => 'New album #1',
					'sources' => array(),
					'filters' => array(
						'except' => array(),
						'only' => array(),
					),
					'limit_items' => 0,
					'cache_lifetime' => 36000
				)
			),
			'cfg' => array(
				'layout_type' => 'grid',
				'layout_cols' => 3,
				'layout_rows' => 4,
				'layout_gutter' => '30',
				'layout_show_albums' => 1,
				'layout_show_loadmore' => 1,
				'layout_loadmore_text' => 'Load more',
				'layout_infinite_scroll' => 0,
				'display_style' => 'default',
				'display_link_mode' => 'popup',
				'display_popup_img_size' => 'try_to_fit',
				'display_thumbs_likes' => 1,
				'display_thumbs_comments' => 1,
				'display_thumbs_plays' => 1,
				'display_thumbs_description' => 1,
				'display_popup_user' => 1,
				'display_popup_instagram_link' => 1,
				'display_popup_likes' => 1,
				'display_popup_comments' => 1,
				'display_popup_plays' => 1,
				'display_popup_date' => 1,
				'display_popup_description' => 1,
				'display_popup_comments_list' => 1,
				'display_popup_loop_video' => 0,
				
				'colors_gallery_bg' => 'rgba(255,255,255,0)',
				'colors_album_btn_bg' => 'rgba(255,255,255,1)',
				'colors_album_btn_text' => 'rgba(0,185,255,1)',
				'colors_album_btn_hover_bg' => 'rgba(0,185,255,1)',
				'colors_album_btn_hover_text' => 'rgba(255,255,255,1)',
				'colors_more_btn_bg' => 'rgba(214,103,205,1)',
				'colors_more_btn_text' => 'rgba(255,255,255,1)',
				'colors_more_btn_hover_bg' => 'rgba(255,255,255,1)',
				'colors_more_btn_hover_text' => 'rgba(214,103,205,1)',
				
				'colors_thumb_overlay_bg' => 'rgba(0,0,0,0.5)',
				'colors_thumb_overlay_text' => 'rgba(255,255,255,1)',
			),
		);
	private $_validParamNames = array('layout_type','layout_cols','layout_rows','layout_gutter','layout_show_albums','layout_show_loadmore','layout_loadmore_text','display_style','display_link_mode','display_thumbs_likes','display_thumbs_comments','display_thumbs_plays','display_popup_likes','display_popup_comments','display_popup_comments_list','display_popup_description','display_popup_username','display_popup_instagram_link','display_popup_date','display_popup_user','display_popup_plays','display_popup_img_size','display_popup_loop_video','display_thumbs_description','colors_gallery_bg','colors_album_btn_bg','colors_album_btn_text','colors_album_btn_hover_bg','colors_album_btn_hover_text','colors_more_btn_bg','colors_more_btn_text','colors_more_btn_hover_bg','colors_more_btn_hover_text','colors_thumb_overlay_bg','colors_thumb_overlay_text','layout_infinite_scroll');
	private $_items = array();
	private $_error = '';
	
	
	public function __contruct(){

	}
	
	public function bindPost($post){
		if(!$post || self::ING_WP_POST_TYPE != get_post_type((int)$post->ID)) {
			$this->_error = __('Gallery not found','ingallery');
			return false;
		}
		$this->setStored('id',(int)$post->ID);
		$this->setStored('name',$post->post_title);
		$decoded = base64_decode($post->post_content);
		if($post->post_content=='' || $decoded==false || substr($decoded,0,1)!=='{'){
			$this->_error = __('Wrong gallery format','ingallery');
			return false;
		}
		$this->_data = json_decode(base64_decode($post->post_content),true);
		return true;
	}
	
	public function getAlbumDefaults(){
		return array(
			'id' => 'someid',
			'name' => 'New album #1',
			'sources' => array(),
			'filters' => array(
				'except' => array(),
				'only' => array(),
			),
			'limit_items' => 0,
			'cache_lifetime' => 36000
		);
	}
	
	public function getItems($start=0,$limit=0){
		$this->loadItems();
		if($start==0 && $limit==0){
			return $this->_items;
		}
		else if($start>0 && $limit==0){
			return array_slice($this->_items, $start);
		}
		else{
			return array_slice($this->_items, $start, $limit);
		}
	}
	
	public function countItems(){
		$this->loadItems();
		return count($this->_items);	
	}
		
	public function loadItems(){
		if(count($this->_items)){
			return false;
		}
		$items = array();
		foreach($this->get('albums') as $album){
			$aItems = array();
			foreach($album['sources'] as $source){
				$aItems = array_merge($aItems,ingalleryHelper::getMediaListFromSource($source,$album));
			}
			$aItems = ingalleryHelper::filterMedia($aItems,$album['filters']);
			usort($aItems,'ingalleryHelper::orderMedia');
			if($album['limit_items']>0 && count($aItems)>$album['limit_items']){
				$aItems = array_slice($aItems,0,$album['limit_items']);
			}
			$items = array_merge($items,$aItems);
		}
		unset($aItems);
		$this->_items = $items;
		unset($items);
		usort($this->_items,'ingalleryHelper::orderMedia');
		return true;
	}
	
	public function hasItemsPage($pageNum){
		$limit = (int)$this->get('cfg/layout_cols')*(int)$this->get('cfg/layout_rows');
		$pageMultiplier = max(0,($pageNum-1));
		$amount = $pageMultiplier*$limit;
		return $this->countItems()>$amount;
	}
	
	public function getItemsPage($pageNum){
		$limit = (int)$this->get('cfg/layout_cols')*(int)$this->get('cfg/layout_rows');
		$start = ($pageNum-1)*$limit;
		$this->loadItems();
		return $this->getItems($start,$limit);
	}
		
	public function get($name,$default=null){
		if(isset($this->_data[$name])){
			return $this->_data[$name];
		}
		else if(strpos($name,'/')!==false){
			$parts = explode('/',$name);
			$result = $this->_data;
			foreach($parts as $part){
				if(isset($result[$part])){
					$result = $result[$part];
				}
				else{
					$result = $default;
					break;
				}
			}
			return $result;
		}
		return $default;
	}
	
	public function set($name,$val){
		if(strpos($name,'/')!==false){
			$parts = explode('/',$name);
			$lastPart = count($parts)-1;
			$pointer = &$this->_data;
			foreach($parts as $k=>$part){
				if($k==$lastPart){
					$pointer[$part] = $val;
					break;
				}
				if(!isset($pointer[$part]) || !is_array($pointer[$part])){
					$pointer[$part] = array();
				}
				$pointer = &$pointer[$part];
			}
			unset($pointer);
		}
		else{
			$this->_data[$name] = $val;
		}
	}
	
	public function getStored($name,$default=null){
		if(isset($this->_stored[$name])){
			return $this->_stored[$name];
		}
		return $default;
	}
	
	public function setStored($name,$val){
		$this->_stored[$name] = $val;
	}
			
	public function getJSON(){
		$obj = $this->_data;
		foreach($obj['albums'] as $k=>$v){
			$obj['albums'][$k]['sources'] = implode(',',$obj['albums'][$k]['sources']);
			$obj['albums'][$k]['filters']['except'] = implode(',',$obj['albums'][$k]['filters']['except']);
			$obj['albums'][$k]['filters']['only'] = implode(',',$obj['albums'][$k]['filters']['only']);
		}
		return json_encode($obj);
	}
	
	public function bind($input){
		$this->_data = array();
		$this->setStored('id',0);
		$this->setStored('name','');
		if(isset($input['id'])){
			$this->setStored('id',(int)$input['id']);
		}
		if(isset($input['name'])){
			$this->setStored('name',trim($input['name']));
		}
		if(isset($input['albums']) && is_array($input['albums'])){
			foreach($input['albums'] as $inputAlbum){
				$album = array(
					'name' => '',
					'sources' => array(),
					'filters' => array(
						'except' => array(),
						'only' => array(),
					),
					'limit_items' => 0,
					'cache_lifetime' => 36000
				);
				if(isset($inputAlbum['name'])){
					$album['name'] = trim($inputAlbum['name']);
				}
				if(isset($inputAlbum['sources'])){
					$album['sources'] = ingalleryHelper::sourcesToAttay($inputAlbum['sources']);
				}
				if(isset($inputAlbum['filters'])){
					if(isset($inputAlbum['filters']['only'])){
						$album['filters']['only'] = ingalleryHelper::sourcesToAttay($inputAlbum['filters']['only'],array('videos'));
					}
					if(isset($inputAlbum['filters']['except'])){
						$album['filters']['except'] = ingalleryHelper::sourcesToAttay($inputAlbum['filters']['except'],array('videos'));
					}
				}
				if(isset($inputAlbum['limit_items'])){
					$album['limit_items'] = abs((int)$inputAlbum['limit_items']);
				}
				if(isset($inputAlbum['cache_lifetime'])){
					$album['cache_lifetime'] = abs((int)$inputAlbum['cache_lifetime']);
				}
				$album['id'] = md5(json_encode($album));
				$this->_data['albums'][] = $album;
			}
		}
		if(isset($input['cfg']) && is_array($input['cfg'])){
			foreach($this->_validParamNames as $paramName){
				if(isset($input['cfg'][$paramName]) && !is_array($input['cfg'][$paramName])){
					$this->_data['cfg'][$paramName] = $input['cfg'][$paramName];
				}
				else{
					$this->_data['cfg'][$paramName] = 0;
				}
			}
		}
	}
	
	public function check(){
		if(trim($this->getStored('name'))==''){
			$this->_error = __('Please enter gallery name','ingallery');
			return false;
		}
		if(!is_array($this->get('albums')) || count($this->get('albums'))==0){
			$this->_error = __('You need to create at least one album','ingallery');
			return false;
		}
		foreach($this->get('albums') as $album){
			if(trim($album['name'])==''){
				$this->_error = __('Every album must have a name','ingallery');
				return false;
			}
			else if(count($album['sources'])==0){
				$this->_error = __('Every album must have at least one source','ingallery');
				return false;
			}
		}
		return true;
	}
	
	public function load($id){
		$id = (int)$id;
		$post = get_post($id);
		if(!$post || self::ING_WP_POST_TYPE != get_post_type($id)) {
			$this->_error = __('Gallery not found','ingallery');
			return false;
		}
		$this->setStored('id',(int)$post->ID);
		$this->setStored('name',$post->post_title);
		$decoded = base64_decode($post->post_content);
		if($post->post_content=='' || $decoded==false || substr($decoded,0,1)!=='{'){
			$this->_error = __('Wrong gallery format','ingallery');
			return false;
		}
		$this->_data = json_decode(base64_decode($post->post_content),true);
		if($this->get('cfg/layout_type')=='default'){
			$this->set('cfg/layout_type','grid');
		}
		return true;
	}
		
	public function save(){
		if((int)$this->getStored('id')>0){
			$id = wp_update_post(array(
				'ID' => (int)$this->getStored('id'),
				'post_status' => 'publish',
				'post_title' => $this->getStored('name'),
				'post_content' => base64_encode(json_encode($this->_data))
			));
		}
		else{
			$id = wp_insert_post(array(
				'post_type' => self::ING_WP_POST_TYPE,
				'post_status' => 'publish',
				'post_title' => $this->getStored('name'),
				'post_content' => base64_encode(json_encode($this->_data))
			));
		}
		if(is_numeric($id) && $id>0){
			$this->setStored('id',(int)$id);
			return true;
		}
		else if(is_wp_error($id)){
			$this->_error = WP_Error::get_error_message($id->get_error_code());
		}
		else{
			$this->_error = __('WP system error','ingallery');
		}
		return false;
	}
	
	public function getError(){
		return $this->_error;
	}
}
