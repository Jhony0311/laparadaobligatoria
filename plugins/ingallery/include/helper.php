<?php
defined('ABSPATH') or die(':)');

abstract class ingalleryHelper
{	
	const MATCH_IG_USER = '~^\@([a-z0-9_\-\.]+)$~';
	const MATCH_IG_HASHTAG = '~^\#(\w+)$~u';
	const MATCH_IG_MEDIA_CODE_CLASS = '[a-zA-Z0-9_\-]+';

	static function onInit(){
		load_plugin_textdomain( 'ingallery', false, INGALLERY_PATH.'languages' );
		wp_register_style('google-font-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i&subset=cyrillic,cyrillic-ext,latin-ext');
		wp_register_style('ingallery-colorpicker-styles', plugins_url('assets/colorpicker/jquery.minicolors.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-tokenfield-styles', plugins_url('assets/css/tokenfield.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-app-styles', plugins_url('assets/css/app.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-frontend-styles', plugins_url('assets/css/frontend.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-icon-font', plugins_url('assets/css/ingfont.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-admin-styles', plugins_url('assets/css/backend.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_style('ingallery-slick-styles', plugins_url('assets/slick/slick.css', INGALLERY_FILE), array(), INGALLERY_VERSION);
		wp_register_script('ingallery-slick', plugins_url('assets/slick/slick.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-admin-scripts', plugins_url('assets/js/backend.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-colorpicker', plugins_url('assets/colorpicker/jquery.minicolors.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-tokenfield', plugins_url('assets/js/tokenfield.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-stepper', plugins_url('assets/js/stepper.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-plugin', plugins_url('assets/js/jq-ingallery.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('jq-form', plugins_url('assets/js/jquery.form.min.js', INGALLERY_FILE), array('jquery'), INGALLERY_VERSION);
		wp_register_script('ingallery-app', plugins_url('assets/js/app.js', INGALLERY_FILE), array('jquery','jq-form','ingallery-plugin','ingallery-tokenfield','ingallery-stepper'), INGALLERY_VERSION);
	}
	
	static function sourcesToAttay($str,$add=array()){
		$result = array();
		if(is_string($str)){
			$parts = explode(',',$str);
			foreach($parts as $part){
				$part = trim($part);
				preg_match('~^(https\:\/\/www\.instagram\.com\/p\/'.self::MATCH_IG_MEDIA_CODE_CLASS.')~',$part,$img);
				if(preg_match(self::MATCH_IG_USER,$part) || preg_match(self::MATCH_IG_HASHTAG,$part)){
					$result[] = $part;
				}
				else if(is_array($img) && isset($img[1])){
					$result[] = $img[1];
				}
				else if(in_array($part,$add)){
					$result[] = $part;
				}
			}
		}
		return array_unique($result);
	}
	
	static function getSourceData($source){
		if(preg_match(self::MATCH_IG_USER,$source)){
			return array(
				'type' => 'user',
				'value' => substr($source,1),
			);
		}
		else if(preg_match(self::MATCH_IG_HASHTAG,$source)){
			return array(
				'type' => 'hashtag',
				'value' => substr($source,1),
			);
		}
		else{
			preg_match('~^https\:\/\/www\.instagram\.com\/p\/('.self::MATCH_IG_MEDIA_CODE_CLASS.')~',$source,$img);
			if(is_array($img) && isset($img[1])){
				return array(
					'type' => 'picture',
					'value' => $img[1],
				);
			}
		}
		return false;
	}
	
	static function loadUserData($username){
		if($username==''){
			return false;
		}
		$data = self::getData('https://www.instagram.com/'.$username.'/?__a=1',array(),36000);
		if(is_array($data) && isset($data['user']) && isset($data['user']['username']) && $data['user']['username']==$username){
			return $data['user'];
		}
		return false;
	}
	
	static function getMediaListFromSource($sourceStr,$album,$bindData=array()){
		$result = array();
		$cacheLifetime = (int)$album['cache_lifetime'];
		$source = self::getSourceData($sourceStr);
		if($source && $source['type']=='user'){
			$userData = self::loadUserData($source['value']);
			if(is_array($userData)){
				$media = self::getData('https://www.instagram.com/query/',array(
					'q'=>'ig_user('.$userData['id'].') { media.after(0, 200) { count, nodes { id, caption, code, comments { count }, date, dimensions { height, width }, display_src, id, is_video, likes { count }, owner { id, username, full_name, profile_pic_url }, thumbnail_src, video_url, location { name, id }, caption }} }'),$cacheLifetime);
				unset($userData);
				if(is_array($media) && isset($media['media']) && isset($media['media']['nodes']) && is_array($media['media']['nodes'])){
					foreach($media['media']['nodes'] as $node){
						$result[] = self::makeGalleryItem($node,$album);
					}
				}
				unset($media);
			}
			unset($data);
		}
		else if($source && $source['type']=='hashtag'){
			$hashData = self::getData('https://www.instagram.com/explore/tags/'.$source['value'].'/?__a=1',array(),$cacheLifetime);
			$mediaItems = array();
			if(is_array($hashData) && isset($hashData['tag']) && isset($hashData['tag']['media']) && isset($hashData['tag']['media']['nodes']) && is_array($hashData['tag']['media']['nodes'])){
				if(count($hashData['tag']['media']['nodes'])){
					$mediaItems = $hashData['tag']['media']['nodes'];
				}
				else if(isset($hashData['tag']['top_posts']) && isset($hashData['tag']['top_posts']['nodes']) && is_array($hashData['tag']['top_posts']['nodes'])){
					$mediaItems = $hashData['tag']['top_posts']['nodes'];
				}
			}
			if(count($mediaItems)){
				foreach($mediaItems as $node){
					$result = array_merge($result,self::getMediaListFromSource('https://www.instagram.com/p/'.$node['code'].'/',$album,$node));
				}
				$media = self::getData('https://www.instagram.com/query/',array(
					'q'=>'ig_hashtag(' . $source['value'] . ') { media.after('.$hashData['tag']['media']['page_info']['end_cursor'].', 200) { count, nodes { id, caption, code, comments { count }, date, dimensions { height, width }, display_src, id, is_video, likes { count }, owner { id, username, full_name, profile_pic_url }, thumbnail_src, video_url, location { name, id }, caption }} }',
					'ref'=>'tags::show',
					),$cacheLifetime);
				unset($hashData);
				if(is_array($media) && isset($media['media']) && isset($media['media']['nodes']) && is_array($media['media']['nodes'])){
					foreach($media['media']['nodes'] as $node){
						$result[] = self::makeGalleryItem($node,$album);
					}
				}
				unset($media);
			}
			
		}
		else if($source && $source['type']=='picture'){
			$data = self::getData('https://www.instagram.com/p/'.$source['value'].'/?__a=1',array(),$cacheLifetime);
			if(is_array($data) && isset($data['media']) && is_array($data['media']) && count($data['media'])){
				$result[] = self::makeGalleryItem($data['media'],$album,$bindData);
			}
		}
		return $result;
	}
	
	static function makeGalleryItem($node,$album,$bindData=array()){
		$item = array_merge(array(
			'caption' => '',
		),$bindData,$node);
		$item['album_id'] = $album['id'];
		preg_match_all('~\#(\w+)~u',$item['caption'],$hashtags);
		$item['hashtags'] = array();
		if(isset($hashtags[1]) && is_array($hashtags[1])){
			foreach($hashtags[1] as $hashtag){
				$item['hashtags'][] = $hashtag;
			}
		}
		$item['video_url'] = (isset($item['video_url'])?$item['video_url']:'');
		$item['is_video'] = (isset($item['is_video'])?$item['is_video']:false);
		$item['thumbnail_src'] = (isset($item['thumbnail_src'])?$item['thumbnail_src']:$item['display_src']);
		return $item;
	}
	
	static function setCookie($cookie){
		$cacheID = 'ing_cookie';
		set_transient($cacheID, json_encode($cookie), 172800);
	}
	
	static function getCookie(){
		$cacheID = 'ing_cookie';
		$cached = get_transient($cacheID);
		if($cached!==false){
			return json_decode($cached,true);
		}
		$params = array( 
			'method' => 'GET',
			'headers' => array(
				'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
				'Origin' => 'https://www.instagram.com',
				'Referer' => 'https://www.instagram.com',
				'Connection' => 'close'
			)
		);
		if(!class_exists('WP_Http')) include_once(ABSPATH.WPINC.'/class-http.php'); 
		if(!class_exists('WP_Http_Cookie')) include_once(ABSPATH.WPINC.'/class-wp-http-cookie.php'); 
		$request = new WP_Http;
		$result = $request->request('https://www.instagram.com/p/BKTCB3HAYvn/', $params);
		if(is_wp_error($result)) {
		   throw new Exception('WP Error: '.$result->get_error_message());
		}
		$newCookie = array();
		if(isset($result['cookies']) && is_array($result['cookies'])){
			foreach($result['cookies'] as $_cookie){
				if($_cookie instanceof WP_Http_Cookie){
					$newCookie[$_cookie->name] = $_cookie->value;
				}
			}
			self::setCookie($newCookie);
		}
		return $newCookie;
	}
			
	static function getData($url,$post,$cacheLifetime,$strict=false){
		$cookie = self::getCookie();
		$cacheID = 'ing_r_'.md5($url.(count($post)?http_build_query($post):''));
		$cached = get_transient($cacheID);
		if($cacheLifetime>0 && $cached!==false){
			return json_decode($cached,true);
		}
		else{
			delete_transient($cacheID);
		}
		
		if(!class_exists('WP_Http')) include_once(ABSPATH.WPINC.'/class-http.php'); 
		if(!class_exists('WP_Http_Cookie')) include_once(ABSPATH.WPINC.'/class-wp-http-cookie.php'); 
		$params = array( 
			'method' => 'GET',
			'headers' => array(
				'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
				'Origin' => 'https://www.instagram.com',
				'Referer' => 'https://www.instagram.com',
				'Connection' => 'close'
			)
		);
		if(count($post)){
			$params['method'] = 'POST';
			$params['body'] = $post;
		}
		if(count($cookie)){
			$params['headers']['X-Csrftoken'] = $cookie['csrftoken'];
			$params['headers']['X-Requested-With'] = 'XMLHttpRequest';
			$params['headers']['X-Instagram-Ajax'] = '1';
			$params['headers']['Cookie'] = array();
			foreach($cookie as $k=>$v){
				$params['headers']['Cookie'][] = $k.'='.$v;
			}
			$params['headers']['Cookie'] = implode('; ',$params['headers']['Cookie']);
		}
		$request = new WP_Http;
		$result = $request->request($url, $params);
		
		if(is_wp_error($result)) {
		   throw new Exception('WP Error: '.$result->get_error_message());
		}
		if($result['response']['code'] != 200) {
			self::logError('Instagram server responce error. Request: "'.$url.'". Responce:'.(isset($result['body'])?'<pre>'.print_r($result['body'],true).'</pre>':''));
			if($strict){
				throw new Exception('Instagram server responce error. Request: "'.$url.'". Responce:'.(isset($result['body'])?'<pre>'.print_r($result['body'],true).'</pre>':''));
			}
			else{
				$result['body'] = '{}';
			}
		}
		if(count($cookie)==0 && isset($result['cookies']) && is_array($result['cookies'])){
			$newCookie = array();
			foreach($result['cookies'] as $_cookie){
				if($_cookie instanceof WP_Http_Cookie){
					$newCookie[$_cookie->name] = $_cookie->value;
				}
			}
			self::setCookie($newCookie);
		}
		if(!isset($result['body']) || !self::isJSON($result['body'])){
			throw new Exception('Instagram server invalid responce');
		}
		if($cacheLifetime>0){
			set_transient($cacheID, $result['body'], $cacheLifetime);
		}
		return json_decode($result['body'],true);
	}
	
	static function filterMedia($items,$filters){
		$result = array();
		if(count($filters['except'])==0 && count($filters['only'])==0){
			return $items;
		}
		$addedIDs = array();
		if(count($filters['only'])>0){
			foreach($filters['only'] as $onlySource){
				if($onlySource=='videos'){
					foreach($items as $item){
						if(in_array($item['code'],$addedIDs)){
							continue;
						}
						if($item['is_video']){
							$result[] = $item;
							$addedIDs[] = $item['code'];
						}
					}
				}
				else{
					$source = self::getSourceData($onlySource);
					if($source){
						foreach($items as $item){
							if(in_array($item['code'],$addedIDs)){
								continue;
							}
							$toAdd = false;
							if($source['type']=='user' && $source['value']==$item['owner']['username']){
								$toAdd = true;
							}
							else if($source['type']=='hashtag' && in_array($source['value'],$item['hashtags'])){
								$toAdd = true;
							}
							else if($source['type']=='picture' && $source['value']==$item['code']){
								$toAdd = true;
							}
							if($toAdd){
								$result[] = $item;
								$addedIDs[] = $item['code'];
							}
						}
					}
				}
			}
		}
		else{
			$result = $items;
		}
		if(count($filters['except'])>0){
			$exIDs = array();
			foreach($filters['except'] as $exSource){
				if($exSource=='videos'){
					foreach($result as $item){
						if($item['is_video']){
							$exIDs[] = $item['code'];
						}
					}
				}
				else{
					$source = self::getSourceData($exSource);
					if($source){
						foreach($result as $item){
							if($source['type']=='user' && $source['value']==$item['owner']['username']){
								$exIDs[] = $item['code'];
							}
							else if($source['type']=='hashtag' && in_array($source['value'],$item['hashtags'])){
								$exIDs[] = $item['code'];
							}
							else if($source['type']=='picture' && $source['value']==$item['code']){
								$exIDs[] = $item['code'];
							}
						}
					}
				}
			}
			$result2 = array();
			foreach($result as $item){
				if(!in_array($item['code'],$exIDs)){
					$result2[] = $item;
				}
			}
			$result = $result2;
		}
		
		return $result;
	}
	
	static function orderMedia($a,$b){
		if($a['date'] == $b['date']) {
			return 0;
		}
		return ($a['date'] < $b['date']) ? 1 : -1;

	}
	
	static function getPreviewItem($item){
		$result = array(
			'id' => $item['id'],
			'code' => $item['code'],
			'date' => $item['date'],
			'time_passed' => self::getTimePassed($item['date']),
			'full_date' => date('d F Y',$item['date']),
			'date_iso' => date('c',$item['date']),
			'likes' => $item['likes']['count'],
			'comments' => $item['comments']['count'],
			'video_url' => $item['video_url'],
			'owner_id' => $item['owner']['id'],
			'owner_username' => $item['owner']['username'],
			'owner_name' => $item['owner']['full_name'],
			'owner_pic_url' => $item['owner']['profile_pic_url'],
			'is_video' => $item['is_video'],
			'display_src' => $item['display_src'],
			'full_width' => $item['dimensions']['width'],
			'full_height' => $item['dimensions']['height'],
			'ratio' => round((int)$item['dimensions']['width']/(int)$item['dimensions']['height'],5),
			'code' => $item['code'],
			'caption' => self::getMediaDescription($item['caption']),
		);
		
		return $result;
	}
	
	static function getTimePassed($time){
		$time = max(time() - $time,1);
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
		
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.__($text.(($numberOfUnits>1)?'s':'').' ago','ingallery');
		}
	}

	static function getMediaDescription($str){
		$result = preg_replace_callback('~((?:\@[a-z0-9_\-\.]+)|(?:\#\w+)|(?:https?\:\/\/[^\s]+)|(?:www\.[^\s]+))~u',function($mchs){
			$type = substr($mchs[1],0,1);
			$value = substr($mchs[1],1);
			if($type=='@'){
				return '<a href="https://www.instagram.com/'.$value.'/" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
			else if($type=='#'){
				return '<a href="https://www.instagram.com/explore/tags/'.$value.'/" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
			else if($type=='h'){
				return '<a href="'.$mchs[1].'" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
			else if($type=='w'){
				return '<a href="http://'.$mchs[1].'" target="_blank" rel="nofollow">'.$mchs[1].'</a>';
			}
		},$str);
		return $result;
	}
	
	static function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	
	static function getGalleries(){
		$args = wp_parse_args(array(
			'post_status' => 'any',
			'posts_per_page' => 50,
			'offset' => 0,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => ingalleryModel::ING_WP_POST_TYPE
		));
		$q = new WP_Query();
		$posts = $q->query( $args );
		$galleries = array();
		foreach($posts as $post){
			$gal = new ingalleryModel();
			if($gal->bindPost($post)){
				$galleries[] = $gal;
			}
		}
		unset($posts);
		return $galleries;
	}
	
	static function widgetsInit(){
		require_once INGALLERY_PATH.'widgets/wp_widget_ingallery.php';
		register_widget( 'wp_widget_ingallery' );
	}
	
	static function logError($error){
		if(!WP_DEBUG){
			return;
		}
		$str = date('d.m.Y H:i:s')."\t".$error."\n#####################################################\n";
		$logFile = INGALLERY_PATH.'logs/error.log';
		@file_put_contents($logFile,$str,FILE_APPEND);
	}
	
}
