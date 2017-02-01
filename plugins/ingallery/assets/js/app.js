
(function($){
	'use strict';
	
	var wrap,
		cfg,
		html,
		albumTmpl,
		currentPageID = '',
		currentScreen,
		screensBlock,
		appWidth,
		updatePreviewTO=0;
		
	var loaderHTML = '<div class="ingLoaderWrap"><div class="ingLoader">';
        	loaderHTML+= '<div class="cssload-dots"><div class="cssload-dot"></div><div class="cssload-dot"></div><div class="cssload-dot"></div><div class="cssload-dot"></div><div class="cssload-dot"></div></div>';
			loaderHTML+= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg"><defs><filter id="ingGBfilter">';
			loaderHTML+= '<feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="12" ></feGaussianBlur>';
			loaderHTML+= '<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0	0 1 0 0 0	0 0 1 0 0	0 0 0 18 -7" result="goo" ></feColorMatrix>';
			loaderHTML+= '</filter></defs>';
			loaderHTML+= '</svg>';
			loaderHTML+= '<style type="text/css">.cssload-dots{filter: url(#ingGBfilter);-o-filter: url(#ingGBfilter);-ms-filter: url(#ingGBfilter);-webkit-filter: url(#ingGBfilter);-moz-filter: url(#ingGBfilter);}</style>';
		loaderHTML+= '</div></div>';
	
	function init(){
		wrap = $('#ingalleryApp');
		if(wrap.length==0){
			return false;
		}
		cfg = JSON.parse(wrap.attr('data-ingallery'));
		
		appWidth = wrap.width();
		
		wrap.append('<div class="ingallery-app-head"><strong>inGallery</strong> by Maxiolab</div>');
		
		html = '<div class="ingallery-app-menu">';
		html+= '<a href="#" data-href="#ingallery-albums">'+ingallery_ajax_object.lang.albums+'</a>';
		html+= '<a href="#" data-href="#ingallery-layout">'+ingallery_ajax_object.lang.layout+'</a>';
		html+= '<a href="#" data-href="#ingallery-display">'+ingallery_ajax_object.lang.display+'</a>';
		html+= '<a href="#" data-href="#ingallery-colors">'+ingallery_ajax_object.lang.colors+'</a>';
		html+= '</div>';
		wrap.append(html);
		
		html = '<div class="ingallery-app-screens">';
			html+= '<div class="ingallery-app-screens-wrap">';
				html+= '<div class="ingallery-app-screen" id="ingallery-albums"><div class="ingallery-app-albums-wrap"></div>';
					html+= '<a href="#" class="ingallery-app-albums-add-btn" title="'+ingallery_ajax_object.lang.add_album+'"></a>';
				html+= '</div>';
				html+= '<div class="ingallery-app-screen" id="ingallery-layout">';
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.type+'</label>';
						html+= '<select name="gallery[cfg][layout_type]" class="ing-form-control ingParam-cfg-layout_type"><option value="grid">Grid</option><option value="carousel">Carousel</option></select></div>';					
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.cols+'</label><input type="number" name="gallery[cfg][layout_cols]" class="ing-form-stepper ingParam-cfg-layout_cols" value="3" min="1" /></div>';
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.rows+'</label><input type="number" name="gallery[cfg][layout_rows]" class="ing-form-stepper ingParam-cfg-layout_rows" value="4" min="1" /></div>';
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.gutter+'</label><input type="number" name="gallery[cfg][layout_gutter]" class="ing-form-stepper ingParam-cfg-layout_gutter" value="30" min="0" />';
                    	html+= '<i class="ing-helptext">'+ingallery_ajax_object.lang.size_in_px+'</i></div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="layout_show_loadmore" class="ing-form-checkbox ingParam-cfg-layout_show_loadmore" name="gallery[cfg][layout_show_loadmore]" value="1" />';
						html+= '<label for="layout_show_loadmore"> '+ingallery_ajax_object.lang.show_loadmore+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="layout_infinite_scroll" class="ing-form-checkbox ingParam-cfg-layout_infinite_scroll" name="gallery[cfg][layout_infinite_scroll]" value="1" />';
						html+= '<label for="layout_infinite_scroll"> '+ingallery_ajax_object.lang.infinite_scroll+'</label>';
					html+= '</div>';
				html+= '</div>';
				html+= '<div class="ingallery-app-screen" id="ingallery-display">';
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.style+'</label><select name="gallery[cfg][display_style]" class="ing-form-control ingParam-cfg-display_style">';
						html+= '<option>default</option><option>flipcards</option><option>circles</option><option>circles2</option><option>dribbble</option>';
						html+= '</select></div>';
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.display_mode+'</label><select name="gallery[cfg][display_link_mode]" class="ing-form-control ingParam-cfg-display_link_mode">';
                    	html+= '<option value="popup">'+ingallery_ajax_object.lang.show_in_popup+'</option><option value="link">'+ingallery_ajax_object.lang.link_to_instagram+'</option>';
                    html+= '</select></div>';
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.popup_img_size+'</label><select name="gallery[cfg][display_popup_img_size]" class="ing-form-control ingParam-cfg-display_popup_img_size">';
						html+= '<option value="try_to_fit">'+ingallery_ajax_object.lang.try_to_fit+'</option><option value="full_size">'+ingallery_ajax_object.lang.full_size+'</option>';
						html+= '</select>';
						html+= '<i class="ing-helptext">'+ingallery_ajax_object.lang.popup_img_size_help+'</i>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="layout_show_albums" class="ing-form-checkbox ingParam-cfg-layout_show_albums" name="gallery[cfg][layout_show_albums]" value="1" />';
						html+= '<label for="layout_show_albums"> '+ingallery_ajax_object.lang.show_albums+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.loadmore_text+'</label><input type="text" name="gallery[cfg][layout_loadmore_text]" class="ing-form-control ingParam-cfg-layout_loadmore_text" maxlength="50" /></div>';
					
					html+= '<div class="ingallery-app-subheader">'+ingallery_ajax_object.lang.display_on_thumbs+'</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_thumbs_likes" class="ing-form-checkbox ingParam-cfg-display_thumbs_likes" name="gallery[cfg][display_thumbs_likes]" value="1" />';
						html+= '<label for="display_thumbs_likes"> '+ingallery_ajax_object.lang.likes+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_thumbs_comments" class="ing-form-checkbox ingParam-cfg-display_thumbs_comments" name="gallery[cfg][display_thumbs_comments]" value="1" />';
						html+= '<label for="display_thumbs_comments"> '+ingallery_ajax_object.lang.comments+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_thumbs_description" class="ing-form-checkbox ingParam-cfg-display_thumbs_description" name="gallery[cfg][display_thumbs_description]" value="1" />';
						html+= '<label for="display_thumbs_description"> '+ingallery_ajax_object.lang.description+'</label>';
					html+= '</div>';
					/*html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_thumbs_plays" class="ing-form-checkbox ingParam-cfg-display_thumbs_plays" name="gallery[cfg][display_thumbs_plays]" value="1" />';
						html+= '<label for="display_thumbs_plays"> '+ingallery_ajax_object.lang.video_plays+'</label>';
					html+= '</div>';*/
					html+= '<div class="ingallery-app-subheader">'+ingallery_ajax_object.lang.display_in_popup+'</div>';
					
					
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_user" class="ing-form-checkbox ingParam-cfg-display_popup_user" name="gallery[cfg][display_popup_user]" value="1" />';
						html+= '<label for="display_popup_user"> '+ingallery_ajax_object.lang.user_block+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_instagram_link" class="ing-form-checkbox ingParam-cfg-display_popup_instagram_link" name="gallery[cfg][display_popup_instagram_link]" value="1" />';
						html+= '<label for="display_popup_instagram_link"> '+ingallery_ajax_object.lang.link_user_to_instagram+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_likes" class="ing-form-checkbox ingParam-cfg-display_popup_likes" name="gallery[cfg][display_popup_likes]" value="1" />';
						html+= '<label for="display_popup_likes"> '+ingallery_ajax_object.lang.likes+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_comments" class="ing-form-checkbox ingParam-cfg-display_popup_comments" name="gallery[cfg][display_popup_comments]" value="1" />';
						html+= '<label for="display_popup_comments"> '+ingallery_ajax_object.lang.comments+'</label>';
					html+= '</div>';
					/*html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_plays" class="ing-form-checkbox ingParam-cfg-display_popup_plays" name="gallery[cfg][display_popup_plays]" value="1" />';
						html+= '<label for="display_popup_plays"> '+ingallery_ajax_object.lang.video_plays+'</label>';
					html+= '</div>';*/
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_date" class="ing-form-checkbox ingParam-cfg-display_popup_date" name="gallery[cfg][display_popup_date]" value="1" />';
						html+= '<label for="display_popup_date"> '+ingallery_ajax_object.lang.created_date+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_description" class="ing-form-checkbox ingParam-cfg-display_popup_description" name="gallery[cfg][display_popup_description]" value="1" />';
						html+= '<label for="display_popup_description"> '+ingallery_ajax_object.lang.description+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_comments_list" class="ing-form-checkbox ingParam-cfg-display_popup_comments_list" name="gallery[cfg][display_popup_comments_list]" value="1" />';
						html+= '<label for="display_popup_comments_list"> '+ingallery_ajax_object.lang.comments_list+'</label>';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<input type="checkbox" id="display_popup_loop_video" class="ing-form-checkbox ingParam-cfg-display_popup_loop_video" name="gallery[cfg][display_popup_loop_video]" value="1" />';
						html+= '<label for="display_popup_loop_video"> '+ingallery_ajax_object.lang.loop_video+'</label>';
					html+= '</div>';
				html+= '</div>';
				html+= '<div class="ingallery-app-screen" id="ingallery-colors">';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.gallery_bg+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_gallery_bg]" class="ing-form-control ingParam-cfg-colors_gallery_bg ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ingallery-app-subheader">'+ingallery_ajax_object.lang.album_btns_colors+'</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.album_btn_bg+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_album_btn_bg]" class="ing-form-control ingParam-cfg-colors_album_btn_bg ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.album_btn_text+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_album_btn_text]" class="ing-form-control ingParam-cfg-colors_album_btn_text ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.album_btn_hover_bg+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_album_btn_hover_bg]" class="ing-form-control ingParam-cfg-colors_album_btn_hover_bg ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.album_btn_hover_text+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_album_btn_hover_text]" class="ing-form-control ingParam-cfg-colors_album_btn_hover_text ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ingallery-app-subheader">'+ingallery_ajax_object.lang.more_btn_colors+'</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.more_btn_bg+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_more_btn_bg]" class="ing-form-control ingParam-cfg-colors_more_btn_bg ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.more_btn_text+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_more_btn_text]" class="ing-form-control ingParam-cfg-colors_more_btn_text ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.more_btn_hover_bg+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_more_btn_hover_bg]" class="ing-form-control ingParam-cfg-colors_more_btn_hover_bg ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.more_btn_hover_text+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_more_btn_hover_text]" class="ing-form-control ingParam-cfg-colors_more_btn_hover_text ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ingallery-app-subheader">'+ingallery_ajax_object.lang.thumbs_colors+'</div>';

					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.thumb_overlay_bg+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_thumb_overlay_bg]" class="ing-form-control ingParam-cfg-colors_thumb_overlay_bg ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';
					html+= '<div class="ing-form-row">';
						html+= '<label>'+ingallery_ajax_object.lang.thumb_overlay_text+'</label>';
						html+= '<input type="text" name="gallery[cfg][colors_thumb_overlay_text]" class="ing-form-control ingParam-cfg-colors_thumb_overlay_text ing-form-colorpicker" maxlength="150" />';
					html+= '</div>';

				html+= '</div>';
			html+= '</div>';
		html+= '</div>';
		
		
		wrap.append(html);
		screensBlock = wrap.find('.ingallery-app-screens');
		
		
		albumTmpl = '<div class="ingallery-app-album" data-id="{id}">';
			albumTmpl+= '<div class="ingallery-app-album-title">';
				albumTmpl+= '<span>{title}</span>';
				albumTmpl+= '<a href="#" class="ingallery-app-album-moreless-btn"><i class="ing-icon-"></i></a>';
				albumTmpl+= '<a href="#" class="ing-del-btn ingallery-app-album-del"><i class="ing-icon-cancel-1"></i></a>';
			albumTmpl+= '</div>';
			albumTmpl+= '<div class="ingallery-app-album-contents">';
				albumTmpl+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.album_name+'</label><input type="text" name="gallery[albums][{id}][name]" class="ing-form-control ingallery-app-album-name" maxlength="50" /></div>';
				albumTmpl+= '<div class="ing-form-row"><label for="asrc_{id}">'+ingallery_ajax_object.lang.sources+'</label><input type="text" name="gallery[albums][{id}][sources]" class="ing-form-tokenfield ingallery-app-album-sources" id="asrc_{id}" />';
				albumTmpl+= '<i class="ing-helptext">'+ingallery_ajax_object.lang.sources_help+'</i></div>';
				albumTmpl+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.filters+'</label>';
					albumTmpl+= '<div class="ingallery-app-album-filters">';
						albumTmpl+= '<div class="ingallery-app-album-filter">';
							albumTmpl+= '<div class="ing-row">';
								albumTmpl+= '<div class="ing-col"><b>'+ingallery_ajax_object.lang.only+'</b></div>';
								albumTmpl+= '<div class="ing-col"><input type="text" name="gallery[albums][{id}][filters][only]" class="ing-form-tokenfield ingallery-app-album-filters_only" /></div>';
							albumTmpl+= '</div>';
						albumTmpl+= '</div>';
						albumTmpl+= '<div class="ingallery-app-album-filter">';
							albumTmpl+= '<div class="ing-row">';
								albumTmpl+= '<div class="ing-col"><b>'+ingallery_ajax_object.lang.except+'</b></div>';
								albumTmpl+= '<div class="ing-col"><input type="text" name="gallery[albums][{id}][filters][except]" class="ing-form-tokenfield ingallery-app-album-filters_except" /></div>';
							albumTmpl+= '</div>';
						albumTmpl+= '</div>';
					albumTmpl+= '</div>';
					albumTmpl+= '<i class="ing-helptext">'+ingallery_ajax_object.lang.filters_help+'</i>';
				albumTmpl+= '</div>';
				albumTmpl+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.limit+'</label><input type="number" name="gallery[albums][{id}][limit_items]" class="ing-form-stepper ingallery-app-album-limit_items" value="0" min="0" />';
				albumTmpl+= '<i class="ing-helptext">'+ingallery_ajax_object.lang.limit_help+'</i></div>';
				albumTmpl+= '<div class="ing-form-row"><label>'+ingallery_ajax_object.lang.cache_lifetime+'</label>';
				albumTmpl+= '<input type="number" name="gallery[albums][{id}][cache_lifetime]" class="ing-form-stepper ingallery-app-album-cache_lifetime" value="36000" min="0" />';
				albumTmpl+= '<i class="ing-helptext">'+ingallery_ajax_object.lang.cache_lifetime_help+'</i></div>';
			albumTmpl+= '</div>';
		albumTmpl+= '</div>';
				
		
		for(var k in cfg.cfg){
			var _control = wrap.find('.ingParam-cfg-'+k);
			if(_control.is('input[type="checkbox"]')){
				_control.prop('checked',cfg.cfg[k]);
			}
			else{
				_control.val(cfg.cfg[k]);
			}
		}
		for(var i=0;i<cfg.albums.length;i++){
			addAlbum(cfg.albums[i]);
		}
		createInputs();
		
		//wrap.find('.ingallery-app-screen').css('width',appWidth);
		
		wrap.find('.ingallery-app-menu a').on('click',function(){
			switchPage($(this).attr('data-href'));
			return false;
		});
				
		wrap.find('a.ingallery-app-albums-add-btn').on('click',function(){
			addAlbum();
			expandCollapseAlbum(wrap.find('.ingallery-app-album').last());
			return false;
		});
		
		$(document).on('click','a.ingallery-app-album-moreless-btn',function(){
			expandCollapseAlbum($(this).closest('.ingallery-app-album'));
			return false;
		});
		$(document).on('click','a.ingallery-app-album-del',function(){
			removeAlbum($(this).closest('.ingallery-app-album'));
			return false;
		});
		
		wrap.find('input,select').on('change',function(e){
			controlChanged(e);
			updatePreview();
		});
		
		window.setTimeout(function(){
			switchPage('#ingallery-albums');
			expandCollapseAlbum(wrap.find('.ingallery-app-album').first());
			window.setInterval(function(){
				if(!currentScreen){
					return;
				}
				var curHeight = screensBlock.attr('data-height');
				var screenHeight = currentScreen.outerHeight();
				if(!curHeight || curHeight!=screenHeight){
					screensBlock.attr('data-height',screenHeight);
					screensBlock.css('height',screenHeight);
				}
			},200);
		},200);
		if(wrap.attr('data-id') && parseInt(wrap.attr('data-id'))>0){
			loadData();
		}
	}
	
	function expandCollapseAlbum(_album){
		var _albumContents = _album.find('.ingallery-app-album-contents');
		if(_album.hasClass('active')){
			_album.removeClass('active');
			_albumContents.stop().slideUp(300);
		}
		else{
			wrap.find('.ingallery-app-album').removeClass('active').find('.ingallery-app-album-contents').stop().slideUp(300);
			_album.addClass('active');
			_albumContents.stop().slideDown(300);
		}
	}
	
	function addAlbum(_albumData){
		var hasAlbumsNum = wrap.find('.ingallery-app-album').length;
		var aName = (_albumData?_albumData.name:ingallery_ajax_object.lang.new_album+' #'+(hasAlbumsNum+1))
		var albumObj = $(albumTmpl.replace('{title}',aName).replace(/\{id\}/g,hasAlbumsNum));
		albumObj.find('.ingallery-app-album-name').val(aName);
		wrap.find('.ingallery-app-albums-wrap').append(albumObj);
		albumObj.find('.ingallery-app-album-name').on('keydown keyup change',function(){
			albumObj.find('.ingallery-app-album-title span').html(this.value);
		});
		if(_albumData){
			albumObj.find('.ingallery-app-album-sources').val(_albumData.sources);
			albumObj.find('.ingallery-app-album-limit_items').val(_albumData.limit_items);
			albumObj.find('.ingallery-app-album-cache_lifetime').val(_albumData.cache_lifetime);
			if(_albumData.filters.only){
				albumObj.find('.ingallery-app-album-filters_only').val(_albumData.filters.only);
			}
			if(_albumData.filters.except){
				albumObj.find('.ingallery-app-album-filters_except').val(_albumData.filters.except);
			}
		}
		albumObj.find('input,select').on('change',function(e){
			controlChanged(e);
			updatePreview();
		});
		createInputs();
		boxSlideIn(albumObj,checkAlbumsDels);
	}
	
	function removeAlbum(_album){
		boxSlideOut(_album,checkAlbumsDels);
	}
	
	function checkAlbumsDels(){
		var delBtns = wrap.find('a.ingallery-app-album-del');
		if(delBtns.length>1){
			delBtns.stop().fadeIn(100);
		}
		else{
			delBtns.stop().fadeOut(100);
		}
	}
	
	function boxSlideIn(_box,_callback){
		var _del = _box.find('a.ing-del-btn').hide();
		_box.hide();
		_box.stop().slideDown(200,'swing',function(){
			_del.fadeIn(100);
			if(_callback){
				_callback();
			}
		});
	}
	
	function boxSlideOut(_box,_callback){
		_box.find('a.ing-del-btn').hide();
		_box.stop().slideUp(200,'swing',function(){
			_box.remove();
			if(_callback){
				_callback();
			}
			updatePreview();
		});
	}
	
	function controlChanged(e){
		//console.info(e);
	}
	
	function createInputs(){
		wrap.find('.ing-form-tokenfield').tokenfield({
			createTokensOnBlur: true,	
		});
		wrap.find('.ing-form-stepper').ingStepper();
		wrap.find('.ing-form-colorpicker').minicolors({
			control: 'hue',
			defaultValue: '',
			format: 'rgb',
			keywords: '',
			inline: false,
			letterCase: 'lowercase',
			opacity: '0.5',
			position: 'bottom left',
			swatches: [],
			change: function(hex, opacity) {
				var log;
				try {
					log = hex ? hex : 'transparent';
					if( opacity ) log += ', ' + opacity;
					console.log(log);
				} catch(e) {}
			},
			theme: 'default'
		});
	}
	
	function switchPage(pageID){
		if(currentPageID && currentPageID!=pageID){
			wrap.find('.ingallery-app-menu a').removeClass('active');
		}
		currentPageID = pageID;
		currentScreen = wrap.find('.ingallery-app-screen'+pageID);
		wrap.find('.ingallery-app-menu a[data-href="'+pageID+'"]').addClass('active');
		var index = wrap.find('.ingallery-app-screen').index(currentScreen);
		wrap.find('.ingallery-app-screens-wrap').css('margin-left','-'+(index*wrap.width())+'px');
	}
	
	function updatePreview(){
		if(updatePreviewTO){
			window.clearTimeout(updatePreviewTO);
		}
		updatePreviewTO = window.setTimeout(loadData,1000);
	}
	
	function loadData(){
		updatePreviewTO = 0;
		var _container = $('#ingalleryDemoWrap');
		var _form = $('#ingAjaxForm');
		var _actionField = _form.find('#ingAction');
		var _action = _actionField.val();
		_container.html(loaderHTML);
		_actionField.val('preview');
		_form.ajaxSubmit({
			type: 'post',
			dataType: 'json',
			success: function(response, statusText, xhr, $form){
				if(!response){
					_container.html(getErrorHTML(ingallery_ajax_object.lang.system_error));
				}
				if(response.status && response.status=='success'){
					_container.html(response.html);
					window.inGallery.reInit();
					$(window).trigger('resize');
				}
				else if(response.status && response.status=='error'){
					_container.html(getErrorHTML(response.message));
				}
				else{
					_container.html(getErrorHTML(ingallery_ajax_object.lang.system_error));
				}
			},
			error: function(){
				_container.html(getErrorHTML(ingallery_ajax_object.lang.system_error));
			},
			fail: function(){
				_container.html(getErrorHTML(ingallery_ajax_object.lang.system_error));
			}
		});	
		_actionField.val(_action);
	}
	
	function getErrorHTML(msg){
		var html = '<div class="ing-error">';
		html+= '<div class="ing-error-title">'+ingallery_ajax_object.lang.error_title+'</div>';
		html+= '<div class="ing-error-message">'+msg+'</div>';
		html+= '</div>';
		return html;
	}
		
	
	$(document).ready(function(e) {
        init();
    });
	
	
})(jQuery);