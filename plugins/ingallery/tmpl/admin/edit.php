<?php
defined('ABSPATH') or die(':)');

$gallery = $this->get('gallery');

echo '<div class="wrap">';

	echo '<h1>';
		if($gallery->getStored('id')){
			echo esc_html(__('Edit gallery', 'ingallery'));
		}
		else{
			echo esc_html(__('Create gallery', 'ingallery'));
		}
	echo '</h1>';
	
	do_action( 'ingallery_admin_notices' );
	?>
	<form method="post" action="<?php echo admin_url( 'admin-ajax.php');?>" id="ingAjaxForm" data-redirect="<?php echo menu_page_url('ingallery', false);?>">
		<input id="ingAction" type="hidden" name="action" value="save" />
		<input type="hidden" id="ingID" name="gallery[id]" value="<?php echo esc_attr((int)$gallery->getStored('id'));?>" />
		<?php /*wp_nonce_field('save','ingallery_check');*/?>
        
<div id="poststuff">
    <div id="post-body" class="metabox-holder">
        <div id="post-body-content">
            <div id="titlediv">
                <div id="titlewrap">
                	<div style="float:right;">
                        <button class="button button-primary button-large" id="ingBtnApply" name="action_apply" type="button"><?php _e('Save & continue edit','ingallery');?></button>
                        <button class="button button-secondary button-large" id="ingBtnSave" name="action_save" type="button"><?php _e('Save & close','ingallery');?></button>
                    </div>
                    <div style="margin-right:280px;">
                    	<input id="title" type="text" name="gallery[name]" value="<?php echo esc_attr($gallery->getStored('name'));?>" placeholder="<?php echo esc_attr( __( 'Enter gallery name here', 'ingallery'));?>" spellcheck="true" autocomplete="off" />
                    </div>
                </div>
                <?php
                if($gallery->getStored('id')){
					?>
                    <div class="inside">
                        <p class="description">
                            <label for="ingallery-shortcode"><?php _e('Copy this shortcode and paste it into your post, page, or text widget content','ingallery');?>:</label>
                            <span class="shortcode wp-ui-highlight"><input id="ingallery-shortcode" onfocus="this.select();" readonly class="large-text code" value="[ingallery id=&quot;<?php echo $gallery->getStored('id');?>&quot;]" type="text"></span>
                        </p>
                    </div>
                	<?php
				}
				?>
        	</div>
            <div id="postdivrich" class="postarea wp-editor-expand">
            	<div id="ingalleryBackendWrap">
                	<div id="ingAppWrap">
                    	<div id="ingalleryApp" class="ingallery-app" data-ingallery='<?php echo $gallery->getJSON();?>' data-id="<?php echo (int)$gallery->getStored('id');?>"></div>
                    </div>
                    <div id="ingalleryDemoWrap">
                    	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
