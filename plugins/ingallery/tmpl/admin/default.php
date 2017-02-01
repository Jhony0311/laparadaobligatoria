<?php
defined('ABSPATH') or die(':)');

$tbl = $this->get('tbl');
$tbl->prepare_items();

echo '<div class="wrap">';

	echo '<h1>';
		echo esc_html(__('Instagram galleries', 'ingallery'));
		echo ' <a href="' . esc_url(add_query_arg(array('action'=>'create'), menu_page_url( 'ingallery', false ))) . '" class="add-new-h2">' . esc_html( __( 'Add New', 'ingallery' ) ) . '</a>';
	echo '</h1>';
	
	do_action( 'ingallery_admin_notices' );
	
	echo '<form method="get">
		<input type="hidden" name="page" value="'.esc_attr( $_REQUEST['page'] ).'" />';
		$tbl->search_box( __( 'Search gallery', 'ingallery' ), 'ingallery-search' );
		$tbl->display();
	echo '</form>';
echo '</div>';
