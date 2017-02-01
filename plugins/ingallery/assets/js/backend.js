
(function($){
	'use strict';
	
	var msgTimeout=0;
	
	function showLoading(){
		hideLoading(true);
		$('<div id="ing-shade-loading"><i class="ing-icon-spin2 animate-spin"></i></div>').appendTo('body').hide().fadeIn(200);
	}
	
	function hideLoading(force){
		var box = $('#ing-shade-loading');
		if(box.length==0){
			return;
		}
		if(force){
			box.remove();
			return;
		}
		box.fadeOut(200,'swing',function(){
			box.remove();
		});
	}
	
	function showMessage(msg,autohide){
		hideMessage(true);
		var shade = $('<div id="ing-shade"></div>').appendTo('body');
		shade.on('click',function(){
			hideMessage();
		});
		shade.hide().fadeIn(200);
		var box = $('<div id="ing-shade-box"></div>').appendTo('body');
		box.append(msg);
		box.css('opacity',0).css('margin-top','-50px');
		box.animate({
			'opacity': 1,
			'margin-top': 0
		},200,'swing');
		if(autohide){
			msgTimeout = window.setTimeout(function(){
				hideMessage();	
			},3000);
		}
	}
	
	function hideMessage(force){
		if(msgTimeout){
			window.clearTimeout(msgTimeout);
			msgTimeout = 0;
		}
		var shade = $('#ing-shade');
		var box = $('#ing-shade-box');
		if(shade.length==0 || box.length==0){
			return false;
		}
		if(force){
			shade.remove();
			box.remove();
			return;
		}
		shade.fadeOut(200,'swing',function(){
			shade.remove();
		});
		box.animate({
			'opacity': 0,
			'margin-top': 50
		},200,'swing',function(){
			box.remove();
		});
	}
	
	function getErrorHTML(msg){
		var html = '<div class="ing-error">';
		html+= '<div class="ing-error-title">'+ingallery_ajax_object.lang.error_title+'</div>';
		html+= '<div class="ing-error-message">'+msg+'</div>';
		html+= '</div>';
		return html;
	}
	
	function getSuccessHTML(msg){
		var html = '<div class="ing-success">';
		html+= '<div class="ing-success-title">'+ingallery_ajax_object.lang.congrats+'</div>';
		html+= '<div class="ing-success-message">'+msg+'</div>';
		html+= '</div>';
		return html;
	}
	
	$(document).ready(function(e) {
		
		window.inGallery.setRequestURI(ingallery_ajax_object.ajax_url);
		window.inGallery.addRequestData = function(gallery){
			if(!gallery.parent().is('#ingalleryDemoWrap')){
				return '';
			}
			var form = $('#ingAjaxForm');
			var actionField = form.find('#ingAction');
			var action = actionField.val();
			actionField.val('preview');
			var data = form.serialize();
			actionField.val(action);
			return data;
		}
		
        $('#ingAjaxForm').on('submit',function(){
			return false;
		});
		$('#ingBtnApply, #ingBtnSave').on('click',function(){
			var form = $('#ingAjaxForm');
			var isApply = $(this).is('#ingBtnApply');
			showLoading();
			form.ajaxSubmit({
				type: 'post',
				dataType: 'json',
				success: function(response, statusText, xhr, $form){
					hideLoading();
					if(response.status && response.status=='success'){
						showMessage(getSuccessHTML(response.message),isApply);
						if(!isApply){
							window.location.href = form.attr('data-redirect');
						}
						else{
							form.find('#ingID').val(response.id);
						}
					}
					else if(response.status && response.status=='error'){
						showMessage(getErrorHTML(response.message));
					}
					else{
						showMessage(getErrorHTML(ingallery_ajax_object.lang.system_error));
					}
				},
				error: function(){
					hideLoading();
					showMessage(getErrorHTML(ingallery_ajax_object.lang.system_error));
				},
				fail: function(){
					hideLoading();
					showMessage(getErrorHTML(ingallery_ajax_object.lang.system_error));
				}
			});	
			return false;
		});
    });
	
})(jQuery);