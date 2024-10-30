<?php

function current_location_shortcode($atts, $content = null) {
	return null;
}

function current_location_post($content) {
	global $post;
	$counter = substr_count($content,'[current-location');
	$tempArray = array('current_location_date','current_location_static','current_location_city', 'current_location_accuracy', 'current_location_coords');
	if ($counter > 0) {
		$startingCL = strpos($content, '[current-location');
		$endingCL = strpos($content,']',$startingCL);
		$tag = substr($content, $startingCL, ($endingCL-$startingCL+1));
		$tempOutput = "";
		foreach ($tempArray as $metaName) :

			if (get_post_meta($post->ID, $metaName, true) != "")  {
				$tempOutput .= get_post_meta($post->ID, $metaName, true);
			}
		endforeach;
		if ($tempOutput != "")
			$tempOutput = __("<abbr title=\"My Current Location\">$tempOutput</abbr>", 'current-location');
		if ($tempOutput == "")
			$tempOutput = __("&laquo;You didn't select any Current Location Information to Display. Edit your Post.&raquo;", 'current-location');
		$content = str_replace($tag, $tempOutput, $content);
	}
	return $content;
}

function current_location_meta_boxes() {

	/* Array of the meta box options. */
	$meta_boxes = array(
		'date' => array('name' => 'current_location_date', 'title' => __('Display Date', 'current-location'), 'type' => 'checkbox', 'CLType' => 'date'), 
		'static' => array('name' => 'current_location_static', 'title' => __('Display Static Image', 'current-location'), 'type' => 'checkbox', 'CLType' => 'static'),
		'coords' => array('name' => 'current_location_coords', 'title' => __('Display Coordinates', 'current-location'), 'type' => 'checkbox', 'CLType' => 'coords'),
		'accuracy' => array('name' => 'current_location_accuracy', 'title' => __('Display Accuracy In Meters', 'current-location'), 'type' => 'checkbox', 'CLType' => 'accuracy'),
		'city' => array('name' => 'current_location_city', 'title' => __('Display Reverse Geocode (Usually City)', 'current-location'), 'type' => 'checkbox', 'CLType' => 'city')
	);
	return apply_filters('current_location_meta_boxes', $meta_boxes);
}
function post_meta_boxes() {
	global $post;
	$meta_boxes = current_location_meta_boxes();
	?><ul><?php	
	foreach ($meta_boxes as $meta) :
	
		$value = get_post_meta($post->ID, $meta['name'], true);
		
		get_meta_text_input($meta, $value);
	
	endforeach;
	?></ul><?php
}
function page_meta_boxes() {
	global $post;
	$meta_boxes = current_location_meta_boxes();
	?><ul><?php
	foreach ( $meta_boxes as $meta ) :
	
		$value = get_post_meta( $post->ID, $meta['name'], true );
		
		get_meta_text_input($meta, $value);
		
	endforeach;
	?></ul><?php
}

function get_meta_text_input($args = array(),$value = false )
{	
	extract( $args );
	$storeValue = display_current_location($CLType);
	
	if ($CLType == "static") {
		if (esc_html($value) != "") {
		?>
    <li style="margin-left:19px;"><span style="text-decoration:underline;"><?php echo $title; ?></span>
    <ul style="margin:0px;padding:0px;">
      <?php if ($storeValue != $value) { ?><li style="margin:0px;padding:0px;"><input style="position:relative;margin-right:5px;" type="radio" name="<?php echo $name; ?>" id="<?php echo $name; ?>1" value="<?php echo htmlspecialchars($storeValue); ?>" /><label for="<?php echo $name; ?>1"><?php _e('Updated Static Map','current-location'); ?></label></li><?php } ?>
      <li style="margin:0px;padding:0px;"><input style="position:relative;margin-right:5px;" type="radio" name="<?php echo $name; ?>" id="<?php echo $name; ?>2" checked="checked" value="<?php echo htmlspecialchars($value); ?>" /><label for="<?php echo $name; ?>2"><?php _e('Saved Static Map','current-location'); ?></label></li>
      <li style="margin:0px;padding:0px;"><input style="position:relative;margin-right:5px;" type="radio" name="<?php echo $name; ?>" id="<?php echo $name; ?>3" value="" /><label for="<?php echo $name; ?>3"><?php _e('Remove saved value','current-location'); ?></label></li>
    </ul><input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
    </li>
    <li><input style="position:relative;margin-right:5px;" type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo htmlspecialchars($storeValue); ?>" /><label for="<?php echo $name; ?>"><?php echo $title; ?></label><input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" /></li>
	  <?php
		} else { ?>
    <li><input style="position:relative;margin-right:5px;" type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo htmlspecialchars($storeValue); ?>" /><label for="<?php echo $name; ?>"><?php echo $title; ?></label><input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" /></li>
    <?php
    }
	} else {
		if (esc_html($value) != "") { //Happens when some meta data is already saved.?>
			<li style="margin-left:19px;"><span style="text-decoration:underline;"><?php echo $title; ?></span>
      <ul style="margin:0px;padding:0px;">
      	<?php if ($storeValue != $value) { ?><li style="margin:0px;padding:0px;"><input style="position:relative;margin-right:5px;" type="radio" name="<?php echo $name; ?>" id="<?php echo $name; ?>1" value="<?php echo htmlspecialchars($storeValue); ?>" /><label for="<?php echo $name; ?>1"><?php echo htmlspecialchars($storeValue); ?> &laquo; <?php _e('Updated Location','current-location'); ?></label></li><?php } ?>
        <li style="margin:0px;padding:0px;"><input style="position:relative;margin-right:5px;" type="radio" name="<?php echo $name; ?>" id="<?php echo $name; ?>2" checked="checked" value="<?php echo htmlspecialchars($value); ?>" /><label for="<?php echo $name; ?>2"><?php echo htmlspecialchars($value); ?></label></li>
        <li style="margin:0px;padding:0px;"><input style="position:relative;margin-right:5px;" type="radio" name="<?php echo $name; ?>" id="<?php echo $name; ?>3" value="" /><label for="<?php echo $name; ?>3"><?php _e('Remove saved value','current-location'); ?></label></li>
      </ul><input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
      </li>
		<?php } else { ?>
			<li><input style="position:relative;margin-right:5px;" type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo htmlspecialchars($storeValue); ?>" /><label for="<?php echo $name; ?>"><?php echo $title; ?> :: <?php echo htmlspecialchars($storeValue) ?></label><input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" /></li>
		<?php }
	}
}

function current_location_save_meta_data($post_id) {
	global $post;

	if ( 'page' == $_POST['post_type'] )
		$meta_boxes = array_merge(current_location_meta_boxes() );
	else
		$meta_boxes = array_merge(current_location_meta_boxes() );

	foreach ( $meta_boxes as $meta_box ) :

		if ( !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename( __FILE__ ) ) )
			return $post_id;

		if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) )
			return $post_id;

		elseif ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		$data = stripslashes( $_POST[$meta_box['name']] );
		
		if ( get_post_meta( $post_id, $meta_box['name'] ) == '' )
			add_post_meta( $post_id, $meta_box['name'], $data, true );

		elseif ( $data != get_post_meta( $post_id, $meta_box['name'], true ) )
			update_post_meta( $post_id, $meta_box['name'], $data );

		elseif ( $data == '' )
			delete_post_meta( $post_id, $meta_box['name'], get_post_meta( $post_id, $meta_box['name'], true ) );

	endforeach;
}

function current_location_create_meta_box() {
	//global $theme_name;

	add_meta_box('page-meta-boxes', 'Current Location Page Options', 'page_meta_boxes', 'page', 'normal');
	add_meta_box('post-meta-boxes', 'Current Location Post Options', 'post_meta_boxes', 'post', 'normal');
}

add_action('admin_menu', 'current_location_create_meta_box');
add_action('save_post', 'current_location_save_meta_data');
add_shortcode('current-location', 'current_location_shortcode');
add_filter('the_content', 'current_location_post');
?>