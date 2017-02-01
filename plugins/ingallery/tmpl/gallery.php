<?php
defined('ABSPATH') or die(':)');

$cfg = $this->get('cfg');
$items = $this->get('items');
$this->set('galleryCSSid','ingallery-'.$this->get('id'));

$margin = ($cfg['layout_gutter']>0?floor($cfg['layout_gutter']/2):0);
$widthPercent = ($cfg['layout_cols']>1?round(100/$cfg['layout_cols'],3):100);
$galCSSid = $this->get('galleryCSSid');


$carouselCfg = '';
if($cfg['layout_type']=='carousel'){
	$carouselCfg = 'data-slick=\'{';
	if($cfg['layout_rows']>1){
		$carouselCfg.= '"rows":'.$cfg['layout_rows'].',"slidesPerRow":'.$cfg['layout_cols'].',"slidesToScroll":1,';
	}
	else{
		$carouselCfg.= '"slidesToShow":'.$cfg['layout_cols'].',"slidesToScroll":'.$cfg['layout_cols'].',';
	}
	$carouselCfg.= '"infinite":'.($cfg['layout_infinite_scroll']?'true':'false').'';
	$carouselCfg.= '}\'';
}
?>
<div class="ingallery ingallery-layout-<?php echo $cfg['layout_type'];?> ingallery-style-<?php echo $cfg['display_style'];?>" id="<?php echo $galCSSid;?>" data-id="<?php echo $this->get('id');?>" data-filtered="0" data-page="<?php echo $this->get('page');?>" data-cfg="<?php echo str_replace('"','&quot;',json_encode($cfg));?>">
	<?php
    if($cfg['layout_type']!='carousel' && $cfg['layout_show_albums'] && count($this->get('albums'))>1){
    ?>
        <div class="ingallery-albums">
            <?php
			$a = 0;
            foreach($this->get('albums') as $album){
				$a++;
                echo '<span class="ingallery-album" data-id="'.$album['id'].'">'.$album['name'].'</span>';
            }
			if($a>1){
				echo '<span class="ingallery-album active" data-id="0">'.__('All albums','ingallery').'</span>';
			}
            ?>
        </div>
    <?php
	}
	?>
	<div class="ingallery-items" data-cols="<?php echo $cfg['layout_cols'];?>" <?php echo $carouselCfg;?>>
		<?php
		foreach($items as $item){
			$this->set('item',$item);
			echo '<div class="ingallery-cell" data-album="'.$item['album_id'].'">';
				echo $this->loadTemplate('item');
			echo '</div>';
		}
		?>
	</div>
	<?php
    if(($cfg['layout_show_loadmore'] || $cfg['layout_infinite_scroll']) && $this->get('has_more')){
		?>
        <div class="ingallery-loadmore <?php echo ($cfg['layout_infinite_scroll']?'ingallery-inf-scroll':'');?>">
        	<span class="ingallery-loadmore-btn"><?php _e($cfg['layout_loadmore_text'],'ingallery');?></span>
        </div>
        <?php	
	}
	?>
</div>
<?php
echo $this->loadTemplate('styles');
