<?php
defined('ABSPATH') or die(':)');

if(!class_exists('WP_List_Table')){
	require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}

class ingalleryTable extends WP_List_Table {

	public function __construct() {
		parent::__construct( array(
			'singular' => 'gallery',
			'plural' => 'galleries',
			'ajax' => false ) );
	}

	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => __( 'Name', 'ingallery' ),
			'shortcode' => __( 'Shortcode', 'ingallery' ),
			'author' => __( 'Author', 'ingallery' ),
			'date' => __( 'Date', 'ingallery' )
		);
		return $columns;
	}
	
	function get_sortable_columns() {
		$columns = array(
			'name' => array( 'name', true ),
			'author' => array( 'author', false ),
			'date' => array( 'date', false ) );

		return $columns;
	}

	function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'ingallery' ) );

		return $actions;
	}
	
	function process_bulk_action() {
        if($this->current_action()=='delete'){
            $ids = ingalleryInput::getVar('gallery',array());
			$deleted = 0;
			foreach($ids as $id){
				if(wp_delete_post((int)$id)){
					$deleted++;
				}
			}
			
        }
        
    }

	function prepare_items() {
		$search = ingalleryInput::getVar('s','');
		$orderBy = ingalleryInput::getCmd('orderby','ID');
		$orderDir = ingalleryInput::getCmd('order','asc');
		$current_screen = get_current_screen();
		$per_page = $this->get_items_per_page( 'ingallery_galleries_per_page' );

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
		
		$this->process_bulk_action();
		
		$args = array(
			'posts_per_page' => $per_page,
			'orderby' => 'title',
			'order' => 'ASC',
			'offset' => ( $this->get_pagenum() - 1 ) * $per_page
		);
		
		if ($search!='') $args['s'] = $search;

		if(in_array($orderBy,array('title','author','date'))){
			$args['orderby'] = $orderBy;
		}
		else{
			$args['orderby'] = 'ID';
		}
		
		if(strtolower($orderDir)=='asc'){
			$args['order'] = 'ASC';
		}
		else{
			$args['order'] = 'DESC';
		}

		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'DESC'
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['post_type'] = ingalleryModel::ING_WP_POST_TYPE;

		$q = new WP_Query();
		$this->items = $q->query( $args );
		$total_items = $q->found_posts;
		$total_pages = ceil( $total_items / $per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page )
		);
	}

	function column_default( $item, $column_name ) {
		return '';
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item->ID );
	}

	function column_name( $post ) {
		$url = admin_url('admin.php?page=ingallery');
		$edit_link = add_query_arg(array('action'=>'edit','id'=>(int)$post->ID), $url );

		$output = sprintf(
			'<a class="row-name" href="%1$s" title="%2$s">%3$s</a>',
			esc_url( $edit_link ),
			esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', 'ingallery' ),
				$post->post_title ) ),
			esc_html( $post->post_title ) );

		$output = sprintf( '<strong>%s</strong>', $output );

		$actions = array(
			'edit' => sprintf( '<a href="%1$s">%2$s</a>',
				esc_url( $edit_link ),
				esc_html( __( 'Edit', 'ingallery' ) ) ) );

		$output.= $this->row_actions( $actions );

		return $output;
	}

	function column_author( $post ) {
		if ( ! $post ) {
			return;
		}
		$author = get_userdata( $post->post_author );
		if ( false === $author ) {
			return;
		}
		return esc_html( $author->display_name );
	}

	function column_shortcode( $post ) {
		$output = '<span class="shortcode"><input type="text"'
			. ' onfocus="this.select();" readonly="readonly"'
			. ' value="' . esc_attr('[ingallery id="'.$post->ID.'"]') . '"'
			. ' class="large-text code" /></span>';

		return $output;
	}

	function column_date( $post ) {
		if ( ! $post )
			return;

		$t_time = mysql2date( __( 'Y/m/d g:i:s A', 'ingallery' ), $post->post_date, true );
		$m_time = $post->post_date;
		$time = mysql2date( 'G', $post->post_date ) - get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 && $time_diff < 24*60*60 )
			$h_time = sprintf( __( '%s ago', 'ingallery' ), human_time_diff( $time ) );
		else
			$h_time = mysql2date( __( 'Y/m/d', 'ingallery' ), $m_time );

		return '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
	}
}
