<?php
defined('ABSPATH') or die(':)');

class wp_widget_ingallery extends WP_Widget {

	function __construct() {
		parent::__construct(
			'wp_widget_ingallery',
			__( 'inGallery' , 'ingallery'), 
			array( 'description' => __( 'Instagram gallery block' , 'ingallery'), )
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		$instance['type'] = 'ingallery';
		$shortcodeAttributes = '';
		foreach( $instance as $argKey => $argVal ){
            if( is_array($argVal)) { continue; }
            $shortcodeAttributes .= " $argKey" . '="'. (string)$argVal . '"';
        }

        echo do_shortcode( '[ingallery ' . $shortcodeAttributes . ' ]' );
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$values = array_merge(
			array('id'=>0),
			$instance
		);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Gallery' , 'ingallery'); ?>:</label> 
            <select id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>">
            	<?php
                foreach(ingalleryHelper::getGalleries() as $gallery){
					echo '<option value="'.$gallery->getStored('id').'" '.((int)$gallery->getStored('id')==(int)$values['id']?'selected="selected"':'').'>'.$gallery->getStored('name').'</option>';
				}
				?>
            </select>
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		foreach ($new_instance as $key => $value) {
			$instance[$key] = ( ! empty( $new_instance[$key] ) ) ? strip_tags( $new_instance[$key] ) : '';
		}

		return $instance;
	}

}
