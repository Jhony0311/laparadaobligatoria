<?php
defined('ABSPATH') or die(':)');

$cfg = $this->get('cfg');
$item = $this->get('item');
$imgRatio = round($item['dimensions']['width']/$item['dimensions']['height'],2);
$attr = ($imgRatio>1?'height':'width');
$previewItemCode = str_replace('"','&quot;',json_encode(ingalleryHelper::getPreviewItem($item)));
?>
<div class="ingallery-item">
	<?php
    if($cfg['display_style']=='flipcards'){
		?>
        <div class="ingallery-item-box">
        	<div class="ingallery-style-flipcards-front">
            	<div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $item['thumbnail_src'];?>" <?php echo $attr;?>="100%">
                </div>
             </div>
            <div class="ingallery-style-flipcards-back">
            	<a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
                	<div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                        <img src="<?php echo $item['thumbnail_src'];?>" <?php echo $attr;?>="100%">
                    </div>
                    <div class="ingallery-item-overlay">
						<?php
                        if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
                            echo '<div class="ingallery-item-stats">';
                                if($cfg['display_thumbs_likes']){
                                    echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart"></i>'.$item['likes']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_comments']){
                                    echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment"></i>'.$item['comments']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_description']){
                                    echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                                }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </a>
            </div>
        </div>
        <?php
	}
    else if($cfg['display_style']=='circles'){
		?>
        <a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-box ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
        	<div class="ingallery-style-circles-front"><img src="<?php echo $item['thumbnail_src'];?>" <?php echo $attr;?>="100%"></div>
            <div class="ingallery-style-circles-back">
                    <div class="ingallery-item-overlay">
						<?php
                        if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
                            echo '<div class="ingallery-item-stats">';
                                if($cfg['display_thumbs_likes']){
                                    echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart"></i>'.$item['likes']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_comments']){
                                    echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment"></i>'.$item['comments']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_description']){
                                    echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                                }
                            echo '</div>';
                        }
                        ?>
                    </div>
            </div>
        </a>
        <?php
	}
    else if($cfg['display_style']=='circles2'){
		?>
        <a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-box ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
        	<div class="ingallery-style-circles2-front"><img src="<?php echo $item['thumbnail_src'];?>" <?php echo $attr;?>="100%"></div>
            <div class="ingallery-style-circles2-back">
                    <div class="ingallery-item-overlay">
						<?php
                        if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
                            echo '<div class="ingallery-item-stats">';
                                if($cfg['display_thumbs_likes']){
                                    echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart"></i>'.$item['likes']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_comments']){
                                    echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment"></i>'.$item['comments']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_description']){
                                    echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                                }
                            echo '</div>';
                        }
                        ?>
                    </div>
            </div>
        </a>
        <?php
	}
	else if($cfg['display_style']=='dribbble'){
		?>
		<a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
			<div class="ingallery-style-dribbble-wrap ingallery-item-box">
                <div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $item['thumbnail_src'];?>" <?php echo $attr;?>="100%">
                </div>
                <div class="ingallery-item-overlay">
                    <?php
                    if($cfg['display_thumbs_description']){
                        echo '<div class="ingallery-item-stats">';
                            if($cfg['display_thumbs_description']){
                                echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                            }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <?php
            if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments']){
				echo '<div class="ingallery-style-dribbble-stats">';
					if($cfg['display_thumbs_likes']){
						echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart"></i>'.$item['likes']['count'].'</span>';
					}
					if($cfg['display_thumbs_comments']){
						echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment"></i>'.$item['comments']['count'].'</span>';
					}
				echo '</div>';
			}
			?>
		</a>
		<?php
	}
	else{
		?>
		<a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-box ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
			<div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
				<img src="<?php echo $item['thumbnail_src'];?>" <?php echo $attr;?>="100%">
			</div>
			<div class="ingallery-item-overlay">
				<?php
				if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
					echo '<div class="ingallery-item-stats">';
						if($cfg['display_thumbs_likes']){
							echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart"></i>'.$item['likes']['count'].'</span>';
						}
						if($cfg['display_thumbs_comments']){
							echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment"></i>'.$item['comments']['count'].'</span>';
						}
						if($cfg['display_thumbs_description']){
							echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
						}
					echo '</div>';
				}
				?>
			</div>
		</a>
		<?php
	}
	?>
</div>