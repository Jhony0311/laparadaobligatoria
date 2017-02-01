

(function($){
	'use strict';
	
	var squareBoxesTimeout = 0;
	var fitPopupTimeout = 0;
	var requestURI = ingallery_ajax_object.ajax_url;
	var popupShown = false;
	var commentsCache = {};
	var bodyOverflow = '';
	
	function setSquareBoxes(){
		if(squareBoxesTimeout>0){
			window.clearTimeout(squareBoxesTimeout);
			squareBoxesTimeout = 0;
		}
		$('.ingallery-items').each(function(){
			var $this = $(this);
			if($this.width()<500 && $this.attr('data-cols') && $this.attr('data-cols')>1){
				$this.addClass('ing-cols-collapse');
			}
			else{
				$this.removeClass('ing-cols-collapse');
			}
		});
		$('.ingallery-style-flipcards .ingallery-item-box, .ingallery-style-circles .ingallery-item-box, .ingallery-style-circles2 .ingallery-item-box').each(function(){
			var $this = $(this);
			$this.height($this.width());
		});
	}
	
	function updateSquareBoxes(){
		if(squareBoxesTimeout>0){
			window.clearTimeout(squareBoxesTimeout);
			squareBoxesTimeout = 0;
		}
		squareBoxesTimeout = window.setTimeout(setSquareBoxes,150);
	}
	
	function showInPopup(_item){
		if(popupShown){
			updatePopup(_item);
		}
		else{
			createPopup(_item);
		}
	}
	
	function createPopup(_item){
		if(popupShown){
			return;
		}
		var popupHTML;
		popupHTML = '<div id="ingallery-shade">';
			popupHTML+= '<div id="ingallery-popup">';
				popupHTML+= '<div id="ingallery-popup-close"><i class="ing-icon-cancel-1"></i></div>';
				popupHTML+= '<div id="ingallery-popup-wrap">';
					popupHTML+= '<div id="ingallery-popup-wrap-img">';
						popupHTML+= '<div id="ingallery-popup-left"><i class="ing-icon-angle-left"></i></div>';
						popupHTML+= '<div id="ingallery-popup-right"><i class="ing-icon-angle-right"></i></div>';
						popupHTML+= '<div id="ingallery-popup-wrap-img-cnt"></div>';
					popupHTML+= '</div>';
					popupHTML+= '<div id="ingallery-popup-wrap-content"></div>';
				popupHTML+= '</div>';
			popupHTML+= '</div>';
		popupHTML+= '</div>';
		var shade = $(popupHTML);
		var _body = $('body');
		var popup = shade.find('#ingallery-popup');
		_body.append(shade);
		bodyOverflow = _body.css('overflow')||'visible';
		_body.css('overflow','hidden');
		popupShown = true;
		shade.fadeIn(300);
		popup.css('opacity',0).css('margin-top','30px').stop().animate({
			opacity: 1,
			'margin-top': 0
		},300);
		
		shade.on('click',function(e){
			var _target = $(e.target);
			var _close = _target.closest('#ingallery-popup-close');
			if(_target.is(shade) || _close.length){
				closePopup();
			}
		});
		
		$(document).on('keydown',processKeydown);
		
		placeDataToPopup(_item);
	}
	
	function closePopup(){
		if(!popupShown){
			return;
		}
		var shade = $('#ingallery-shade');
		var popup = shade.find('#ingallery-popup');
		var _body = $('body');
		shade.fadeOut(300,'swing',function(){
			shade.remove();
			_body.css('overflow',bodyOverflow);
			popupShown = false;
		});
		popup.stop().animate({
			opacity: 0,
			'margin-top': 30
		},300);
		$(document).off('keydown',processKeydown);
	}
	
	function processKeydown(e){
		if(!e.which){
			return;
		}
		if(e.which==37){
			$('#ingallery-popup-left:visible').click();
		}
		else if(e.which==39){
			$('#ingallery-popup-right:visible').click();
		}
	}
	
	function setFitPopup(){
		if(fitPopupTimeout>0){
			window.clearTimeout(fitPopupTimeout);
			fitPopupTimeout = 0;
		}
		fitPopupTimeout = window.setTimeout(fitPopup,100);
	}
	
	function fitPopup(){
		if(fitPopupTimeout>0){
			window.clearTimeout(fitPopupTimeout);
			fitPopupTimeout = 0;
		}
		if(!popupShown){
			return;
		}
		var popup = $('#ingallery-popup');
		var popupImgWrap = $('#ingallery-popup-wrap-img');
		var popupWrap = $('#ingallery-popup-wrap');
		var winH = $(window).height();
		var winW = $(window).width();
		var imgMode = popupImgWrap.attr('data-imgmode');
		if(popupImgWrap.length==0 || popupWrap.length==0){
			return false;
		}
		if(winW<750){
			popup.addClass('ing-smallest');
			return false;
		}
		else{
			popup.removeClass('ing-smallest');
		}
		var maxH = winH-100;
		var minH = 550;
		var maxw = winW-330-100;
		if(imgMode!='try_to_fit'){
			maxH = Math.min(maxH,popupImgWrap.attr('data-height'));
			maxw = Math.min(maxw,popupImgWrap.attr('data-width'));
		}
		var ratio = popupImgWrap.attr('data-ratio');
		var width = 750;
		var height = width/ratio;
		if(height>maxH){
			height = maxH;
			width = height*ratio;
		}
		if(height<minH){
			height = minH;
			width = height*ratio;
		}
		if(width>maxw){
			width = maxw;
			height = width/ratio;
		}
		var top = Math.max((winH-height)/2,50);
		popup.css('top',top);
		popupWrap.height(height);
		popupImgWrap.width(width);
	}
	
	function updatePopup(_item){
		if(!popupShown){
			return;
		}
		var popupWraps = $('#ingallery-popup-wrap-img-cnt > img,#ingallery-popup-wrap-content');
		popupWraps.stop().animate({
			'opacity': 0
		},100,'swing',function(){
			placeDataToPopup(_item);
			popupWraps.stop().animate({
				'opacity': 1
			},100);
		});
	}
	
	function placeDataToPopup(_item){
		var popupWrap = $('#ingallery-popup-wrap');
		var popupImgWrap = $('#ingallery-popup-wrap-img');
		var popupContentWrap = $('#ingallery-popup-wrap-content');
		if(popupImgWrap.length==0 || popupContentWrap.length==0){
			return false;
		}
		popupImgWrap.attr('data-ratio',_item.ratio);
		popupImgWrap.attr('data-width',_item.full_width);
		popupImgWrap.attr('data-height',_item.full_height);
		popupImgWrap.attr('data-imgmode',_item.cfg.display_popup_img_size);
		var imgPlaceholder = popupImgWrap.find('#ingallery-popup-wrap-img-cnt');
		imgPlaceholder.off('click').removeClass('ing-video').removeClass('ing-playing');
		if(_item.is_video){
			imgPlaceholder.addClass('ing-video');
			var _video = $('<video poster="'+_item.display_src+'" src="'+_item.video_url+'" preload="false" '+(_item.cfg.display_popup_loop_video?'loop="loop"':'')+' webkit-playsinline=""></video>');
			imgPlaceholder.empty().append(_video);
			var domVideo = _video.get(0);
			imgPlaceholder.on('click',function(){
				if(domVideo.paused){
					imgPlaceholder.addClass('ing-playing');
					domVideo.play();
				}
				else{
					imgPlaceholder.removeClass('ing-playing');
					domVideo.pause();
				}
				return false;
			});
		}
		else{
			var _img = $('<img />').css('opacity',0);
			_img.on('load',function(){
				_img.animate({
					'opacity': 1
				},200);
			});
			_img.attr('src',_item.display_src);
			imgPlaceholder.empty().append(_img);
		}
		var contentHTML = '';
		if(_item.cfg.display_popup_user){
			contentHTML+= '<div class="ingallery-popup-content-user">';
			if(_item.cfg.display_popup_instagram_link){
				contentHTML+= '<a href="https://www.instagram.com/'+_item.owner_username+'/" title="'+_item.owner_name+'" target="_blank">';
			}
			contentHTML+= '<img src="'+_item.owner_pic_url+'"> '+_item.owner_username;
			if(_item.cfg.display_popup_instagram_link){
				contentHTML+= '</a>';
			}
			contentHTML+= '</div>';
		}
		if(_item.cfg.display_popup_likes || _item.cfg.display_popup_comments || _item.cfg.display_popup_plays || _item.cfg.display_popup_date){
			contentHTML+= '<div class="ingallery-popup-content-stats">';
			if(_item.cfg.display_popup_date){
				contentHTML+= '<time title="'+_item.full_date+'" datetime="'+_item.date_iso+'">'+_item.time_passed+'</time>';
			}
			if(_item.cfg.display_popup_likes){
				contentHTML+= '<span class="ingallery-popup-content-stats-likes"><i class="ing-icon-heart"></i> '+_item.likes+'</span>';
			}
			if(_item.cfg.display_popup_comments){
				contentHTML+= '<span class="ingallery-popup-content-stats-comments"><i class="ing-icon-comment"></i> '+_item.comments+'</span>';
			}
			contentHTML+= '</div>';
		}
		if(_item.cfg.display_popup_description || _item.cfg.display_popup_comments_list){
			contentHTML+= '<div class="ingallery-popup-content-stretch">';
		}
			if(_item.cfg.display_popup_description){
				contentHTML+= '<div class="ingallery-popup-content-descr">'+_item.caption+'</div>';
			}
			var needToLoadComments = false;
			if(_item.cfg.display_popup_comments_list){
				needToLoadComments = true;
				contentHTML+= '<div class="ingallery-popup-content-comments ing-'+_item.code+' ing-loading">';
				if(commentsCache[_item.code]){
					needToLoadComments = false;
					contentHTML+= commentsCache[_item.code];
				}
				contentHTML+= '</div>';
			}
		if(_item.cfg.display_popup_description || _item.cfg.display_popup_comments_list){
			contentHTML+= '</div>';
		}
		
		if(contentHTML!=''){
			popupWrap.removeClass('noRight');
			popupContentWrap.html(contentHTML);
		}
		else{
			popupWrap.addClass('noRight');
		}
		
		
		if(needToLoadComments){
			$.ajax({
				url: requestURI,
				type: 'get',
				dataType: 'json',
				cache : true,
				data: 'action=comments&media_code='+_item.code,
				success: function(response, statusText, xhr, $form){
					if(response && response.status && response.status=='success'){
						commentsCache[response.media_code] = response.html;
						var comments = $(response.html);
						popupContentWrap.find('.ingallery-popup-content-comments.ing-'+response.media_code).removeClass('ing-loading').append(comments);
						comments.css('opacity',0).stop().animate({
							'opacity': 1
						},300);
					}
				},
			});
		}
		
		var _gallery = _item.jqItem.closest('.ingallery');
		var _items = _gallery.find('a.ingallery-item-link-popup:visible');
		var goPrev = $('#ingallery-popup-left');
		var goNext = $('#ingallery-popup-right');
		var itemIndex = _items.index(_item.jqItem);
		if(itemIndex>0){
			goPrev.show();
			goPrev.off('click').on('click',function(){
				_items.get(itemIndex-1).click();
			});
		}
		else{
			goPrev.hide();
		}
		if(itemIndex+1<_items.length){
			goNext.show();
			goNext.off('click').on('click',function(){
				_items.get(itemIndex+1).click();
			});
		}
		else{
			goNext.hide();
		}
		
		if(itemIndex>_items.length-4){
			_gallery.find('.ingallery-loadmore-btn').click();
		}
		
		fitPopup();
		
		return true;
	}
	
	function getMessageHTML(msg,type){
		var html = '<div class="ingallery-message ing-'+type+'">';
		html+= '<div class="ingallery-message-title">'+ingallery_ajax_object.lang.error_title+'</div>';
		html+= '<div class="ingallery-message-text">'+msg+'</div>';
		html+= '</div>';
		return html;
	}
	
	function filterGallery(gallery,albumID,_force){
		gallery.css('height',gallery.height());
		var oldAlbum = gallery.find('.ingallery-album.active');
		if(oldAlbum.attr('data-id')==albumID && !_force){
			return;
		}
		var newAlbum = gallery.find('.ingallery-album[data-id="'+albumID+'"]');
		var itemsToShow;
		var itemsToHide;
		
		gallery.attr('data-filtered',newAlbum.attr('data-id'));
		oldAlbum.removeClass('active');
		newAlbum.addClass('active');
		
		itemsToHide = gallery.find('.ingallery-cell');
		
		if(newAlbum.attr('data-id')=='0'){
			itemsToShow = gallery.find('.ingallery-cell');
		}
		else{
			itemsToShow = gallery.find('.ingallery-cell[data-album="'+newAlbum.attr('data-id')+'"]');
		}
		itemsToHide.stop().hide();
		itemsToShow.stop().show().css('opacity',0).animate({
			'opacity': 1
		},300);
		gallery.css('height','auto');
		setSquareBoxes();
	}
	
	function init(){
		var galleries = $('.ingallery');
		if(galleries.length==0){
			return false;
		}
		setSquareBoxes();
		galleries.off('updateLayout').on('updateLayout',function(){
			updateSquareBoxes();
		});
		galleries.each(function(){
			var gallery = $(this);
			var loadMoreBtn = gallery.find('.ingallery-loadmore-btn');
			var infScroll = gallery.find('.ingallery-loadmore.ingallery-inf-scroll');
			
			gallery.find('.ingallery-album').off('click').on('click',function(){
				filterGallery(gallery,$(this).attr('data-id'));
			});
			if(gallery.hasClass('ingallery-layout-carousel')){
				gallery.find('.ingallery-items').not('.slick-initialized').slick();
				gallery.off('beforeCarouselChange').on('beforeCarouselChange',function(e,_slick,_prevSlideID,_nextSlideID){
					checkCarouselLoad(gallery,_nextSlideID);
				});
			}
			
			bindItemLinks(gallery);
			
			loadMoreBtn.off('click').on('click',function(){
				var $this = $(this);
				if($this.hasClass('disabled')){
					return false;
				}
				var btnContent = $this.html();
				var currentPage = parseInt(gallery.attr('data-page'));
				currentPage++;
				var data = 'id='+gallery.attr('data-id')+'&page='+currentPage+'&action=ingallery';
				var messageContainer = gallery.find('.ingallery-loadmore');
				var addData = window.inGallery.addRequestData(gallery);
				if(addData){
					data+= '&'+addData;
				}
				$this.addClass('disabled').html('<i class="ing-icon-spin2 animate-spin"></i>');
				$.ajax({
					'url': requestURI,
					'type': 'get',
					'dataType': 'json',
					'data': data,
					'success': function(response, statusText, xhr, $form){
						if(!response){
							messageContainer.html(getMessageHTML(ingallery_ajax_object.lang.system_error,'error'));
							return false;
						}
						if(response.status && response.status=='success'){
							var newItems = $(response.html).find('.ingallery-cell');
							var itemsContainer = gallery.find('.ingallery-items');
							if(gallery.hasClass('ingallery-layout-carousel')){
								itemsContainer.slick('slickAdd',newItems);
							}
							else{
								itemsContainer.append(newItems);
							}
							newItems.css('opacity',0).stop().animate({
								'opacity': 1
							},300);
							bindItemLinks(gallery);
							var galleryFiltered = gallery.attr('data-filtered');
							if(galleryFiltered && galleryFiltered!='' && galleryFiltered!='0'){
								filterGallery(gallery,galleryFiltered,true);
							}
							else{
								updateSquareBoxes();
							}
							gallery.attr('data-page',currentPage);
							if(response.has_more){
								$this.removeClass('disabled').html(btnContent);
								if(gallery.hasClass('ingallery-layout-carousel')){
									checkCarouselLoad(gallery);
								}
								else if(infScroll.length){
									checkInfScroll(loadMoreBtn);
								}
							}
							else{
								var tmp = gallery.find('.ingallery-loadmore');
								tmp.slideUp(200,'swing',function(){
									tmp.remove();	
								});
							}
						}
						else if(response.status && response.status=='error'){
							messageContainer.html(getMessageHTML(response.message,'error'));
						}
						else{
							messageContainer.html(getMessageHTML(ingallery_ajax_object.lang.system_error,'error'));
						}
					},
					error: function(){
						messageContainer.html(getMessageHTML(ingallery_ajax_object.lang.system_error,'error'));
					},
				});	
				
				return false;
			});
			if(gallery.hasClass('ingallery-layout-carousel')){
				loadMoreBtn.click();
			}
			else if(infScroll.length){
				$(window).on('scroll',function(){
					checkInfScroll(loadMoreBtn);
				});
				checkInfScroll(loadMoreBtn);
			}
		});
	}
	
	function checkCarouselLoad(gallery,_nextSlideID){
		var carousel = gallery.find('.ingallery-items').data('ingslick');
		if(!carousel){
			return;
		}
		var curSlide;
		var totalSlides;
		if(_nextSlideID){
			carousel.currentSlide = _nextSlideID;
		}
		if(carousel.options.rows>1){
			curSlide = carousel.currentSlide;
			totalSlides = carousel.slideCount;
			//carousel.options.slidesPerRow
		}
		else{
			curSlide = Math.floor((parseInt(carousel.currentSlide)+1) / parseInt(carousel.options.slidesToShow));
			totalSlides = Math.ceil((parseInt(carousel.slideCount) - parseInt(carousel.options.slidesToShow))/parseInt(carousel.options.slidesToShow));
		}
		if(curSlide>totalSlides-3){
			gallery.find('.ingallery-loadmore-btn').click();
		}
	}
	
	function checkInfScroll(loadMoreBtn){
		if(loadMoreBtn.hasClass('disabled')){
			return false;
		}
		var top = $(window).scrollTop() + ($(window).height()*1.4);
		if(top>loadMoreBtn.offset().top){
			loadMoreBtn.click();
		}
	}
	
	function bindItemLinks(gallery){
		gallery.find('a.ingallery-item-link-popup').off('click').on('click',function(e){
			e.preventDefault();
			var $this = $(this);
			var itemData = $this.attr('data-item');
			var galleryCfg = gallery.attr('data-cfg');
			if(!itemData || !galleryCfg){
				return false;
			}
			var _item = JSON.parse(itemData);
			_item.cfg = JSON.parse(galleryCfg);
			_item.jqItem = $this;
			showInPopup(_item);
			return false;
		});
	}
	
	$(document).ready(function(e) {
		init();
    });
	
	$(window).on('resize',function(){
		updateSquareBoxes();
		setFitPopup();
	});
	
	window.inGallery = {
		reInit: function(){
			init();
		},
		setRequestURI: function(uri){
			requestURI = uri;
		},
		addRequestData: function(gallery){
			return '';
		}
	}
	
})(jQuery);
