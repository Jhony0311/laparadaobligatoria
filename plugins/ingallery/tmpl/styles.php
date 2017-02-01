<?php
defined('ABSPATH') or die(':)');

$cfg = $this->get('cfg');
$this->set('galleryCSSid','ingallery-'.$this->get('id'));

$margin = ($cfg['layout_gutter']>0?floor($cfg['layout_gutter']/2):0);
$widthPercent = ($cfg['layout_cols']>1?round(100/$cfg['layout_cols'],3):100);
$galCSSid = $this->get('galleryCSSid');
?>
<style type="text/css">
#<?php echo $galCSSid;?>{background:<?php echo $cfg['colors_gallery_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-album{border-color:<?php echo $cfg['colors_album_btn_text'];?>;color:<?php echo $cfg['colors_album_btn_text'];?>;background:<?php echo $cfg['colors_album_btn_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-album:hover,
#<?php echo $galCSSid;?> .ingallery-album.active{color:<?php echo $cfg['colors_album_btn_hover_text'];?>;background:<?php echo $cfg['colors_album_btn_hover_bg'];?>;}
<?php
if($cfg['layout_type']=='carousel'){
	?>
	#<?php echo $galCSSid;?> .ingallery-col,
	#<?php echo $galCSSid;?> .ingallery-cell{padding:<?php echo $margin;?>px;}
	<?php
}
else{
	?>
	#<?php echo $galCSSid;?> .ingallery-items{margin-left:-<?php echo $margin;?>px;margin-right:-<?php echo $margin;?>px;}
	#<?php echo $galCSSid;?> .ingallery-col,
	#<?php echo $galCSSid;?> .ingallery-cell{padding-left:<?php echo $margin;?>px;padding-right:<?php echo $margin;?>px;margin-bottom:<?php echo $cfg['layout_gutter'];?>px;}
	#<?php echo $galCSSid;?> .ingallery-col,
	#<?php echo $galCSSid;?> .ingallery-cell{width:<?php echo $widthPercent;?>%;}
	<?php
}
?>
#<?php echo $galCSSid;?> .ingallery-item-overlay{background:<?php echo $cfg['colors_thumb_overlay_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-item-overlay .ingallery-item-stats{color:<?php echo $cfg['colors_thumb_overlay_text'];?>;}
#<?php echo $galCSSid;?> .ingallery-loadmore-btn{border-color:<?php echo $cfg['colors_more_btn_bg'];?>;color:<?php echo $cfg['colors_more_btn_text'];?>;background:<?php echo $cfg['colors_more_btn_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-loadmore-btn:hover{color:<?php echo $cfg['colors_more_btn_hover_text'];?>;background:<?php echo $cfg['colors_more_btn_hover_bg'];?>;}

</style>