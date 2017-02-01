<?php
defined('ABSPATH') or die(':)');

add_action( 'vc_before_init', 'vc_widget_ingallery' );

function vc_widget_ingallery(){	
	$value = array();
	foreach(ingalleryHelper::getGalleries() as $gal){
		$value[$gal->getStored('name')] = $gal->getStored('id');
	}
	vc_map( array(
		"name"              => __("inGallery", 'ingallery'),
		"description"       => __("inGallery by Maxio lab.", 'ingallery'),
		"base"              => 'ingallery',
		"class"             => "ingallery_ext_vc",
		"controls"          => "full",
		"icon"              => "vc_ingallery",
		"category"          => __('Media', 'ingallery'),
		"params"            => array(
			array(
				"param_name"  => "id",
				"type"        => "dropdown",
				"holder"      => "span",
				"heading"     => __("Gallery", 'ingallery'),
				"value"       => $value,
				"std"         => 0,
			),
		)
	));
}