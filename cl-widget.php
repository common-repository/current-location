<?php

/**
 * CurrentLocationWidget Class
 */
global $CL_Version;
class CurrentLocationWidget extends WP_Widget {
    /** constructor */
    function CurrentLocationWidget() {
        parent::WP_Widget(false, $name = 'Current Location '. CL_VERSION);	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
				?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                  <?php echo display_current_location($instance['show_map'], $instance['show_staticmap'], $instance['show_date'], $instance['show_city'], $instance['show_accuracy'], $instance['show_coords']); ?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['show_staticmap'] = $new_instance['show_staticmap'];
			$instance['show_map'] = $new_instance['show_map'];
			$instance['show_date'] = $new_instance['show_date'];
			$instance['show_city'] = $new_instance['show_city'];
			$instance['show_accuracy'] = $new_instance['show_accuracy'];
			$instance['show_coords'] = $new_instance['show_coords'];
      return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'current-location'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
				 </p>
				 <p>	
					<input value="date" class="checkbox" type="checkbox" <?php checked( $instance['show_date'], 'date' ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
					<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e('Display Date', 'current-location'); ?></label>
         </p>
				 <p>	
					<input value="static" class="checkbox" type="checkbox" <?php checked( $instance['show_staticmap'], 'static' ); ?> id="<?php echo $this->get_field_id( 'show_staticmap' ); ?>" name="<?php echo $this->get_field_name( 'show_staticmap' ); ?>" />
					<label for="<?php echo $this->get_field_id( 'show_staticmap' ); ?>"><?php _e('Display Static Map', 'current-location'); ?></label>
         </p>
         <p>	
					<input value="map" class="checkbox" type="checkbox" <?php checked( $instance['show_map'], 'map' ); ?> id="<?php echo $this->get_field_id( 'show_map' ); ?>" name="<?php echo $this->get_field_name( 'show_map' ); ?>" />
					<label for="<?php echo $this->get_field_id( 'show_map' ); ?>"><?php _e('Display Map - Little Google Map', 'current-location'); ?></label>
         </p>
				 <p>
					<input value="city" class="checkbox" type="checkbox" <?php checked( $instance['show_city'], 'city' ); ?> id="<?php echo $this->get_field_id( 'show_city' ); ?>" name="<?php echo $this->get_field_name( 'show_city' ); ?>" />
					<label for="<?php echo $this->get_field_id( 'show_city' ); ?>"><?php _e('Display Reverse Geocode (Usually City)', 'current-location'); ?></label>
         </p>
			   <p>
					<input value="accuracy" class="checkbox" type="checkbox" <?php checked( $instance['show_accuracy'], 'accuracy' ); ?> id="<?php echo $this->get_field_id( 'show_accuracy' ); ?>" name="<?php echo $this->get_field_name( 'show_accuracy' ); ?>" />
					<label for="<?php echo $this->get_field_id( 'show_accuracy' ); ?>"><?php _e('Display Accuracy In Meters', 'current-location'); ?></label>
         </p>
				   <p>
						<input value="coords" class="checkbox" type="checkbox" <?php checked( $instance['show_coords'], 'coords' ); ?> id="<?php echo $this->get_field_id( 'show_coords' ); ?>" name="<?php echo $this->get_field_name( 'show_coords' ); ?>" />
						<label for="<?php echo $this->get_field_id( 'show_coords' ); ?>"><?php _e('Display Coordinates', 'current-location'); ?></label>
	         </p>
        <?php 
    }
} // CurrentLocationWidget

add_action('widgets_init', create_function('', 'return register_widget("CurrentLocationWidget");'));
?>