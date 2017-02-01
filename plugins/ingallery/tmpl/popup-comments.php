<?php
defined('ABSPATH') or die(':)');

foreach($this->get('comments') as $comment){
	echo '<div class="ingallery-popup-content-comments-item">';
		echo '<strong><a href="https://www.instagram.com/'.$comment['user']['username'].'/" target="_blank" rel="nofollow">@'.$comment['user']['username'].'</a></strong>';
		echo '<span>'.ingalleryHelper::getMediaDescription($comment['text']).'</span>';
	echo '</div>';
}