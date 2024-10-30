<?php

function current_location_tabs() {
     $tabs = array(
          'settings' => 'Settings',
          'examples' => 'Examples',
					'samples' => 'Samples'
     );
     return $tabs;
}
function current_location_page_tabs( $current = 'settings' ) {
     global $CL_Version;
		 if ( isset ( $_GET['tab'] ) ) :
          $current = $_GET['tab'];
     else:
          $current = 'settings';
     endif;
     $tabs = current_location_tabs();
     $links = array();
     foreach( $tabs as $tab => $name ) :
          if ( $tab == $current ) :
               $links[] = "<a class=\"nav-tab nav-tab-active\" href=\"?page=current-location&tab=$tab\">$name</a>";
          else :
               $links[] = "<a class=\"nav-tab\" href=\"?page=current-location&tab=$tab\">$name</a>";
          endif;
     endforeach;
     echo '<div style="background:transparent url('. WP_PLUGIN_URL . '/current-location/icon32.png) no-repeat;float:left;height:36px;margin:14px 6px 0 0;width:36px;"><br /></div>';
		 echo '<h2 class="nav-tab-wrapper">Current Location '. $CL_Version . '&nbsp;';
     foreach ( $links as $link )
          echo $link;
     echo '</h2>';
}
function current_location_page_output() {
	global $current;
	?>
  <div class="wrap">
  	<?php current_location_page_tabs(); ?>
    <?php
		if ( isset ( $_GET['tab'] ) ) :
				$current = $_GET['tab'];
		else:
				$current = 'settings';
		endif;
		switch ($current)
		{
			case 'settings':
				current_location_page_settings();
				break;
			case 'examples':
				current_location_page_examples();
				break;
			case 'samples':
				current_location_page_samples();
				break;
			default:
				current_location_page_settings();
				break;
		}
		?>
  </div><?php
}
function current_location_page_settings() {
	global $CL_Version, $wp_version;
	$update = false;
	$options = get_option("current_location_plugin");
	if (!is_array($options)) {
		$options["current_location_userid"] = "Enter Here";
		$options["current_location_date"] = "M d, Y g:i a";
		$options["current_location_temp_check"] = "";
		$options["current_location_temp_location"] = "";
		$options["current_location_map_width"] = "180";
		$options["current_location_map_height"] = "300";
		$options["current_location_map_type"] = "road";
		$options["current_location_map_zoom"] = "10";
	}

	// If the user has submitted their options, grab them here.
	if ($_POST['CL_SAVE']) {
		//Main settings were updated/saved.
		$options["current_location_userid"] = htmlspecialchars(trim($_POST["user_id"]));
		$options["current_location_date"] = htmlspecialchars(trim($_POST["format_date"]));
		$options["current_location_temp_check"] = htmlspecialchars($_POST["temp_check"]);
		//Checking to see if vacation is checked. 
		if ($options["current_location_temp_check"] == "Vac")
		{
			$options["current_location_temp_location"] = htmlspecialchars(trim($_POST["temp_location"]));
		} else {
			$options["current_location_temp_location"] = "";
		}
		//Map settings were updated/saved. 
		$width = htmlspecialchars(trim($_POST["maps_width"]));
		$height = htmlspecialchars(trim($_POST["maps_height"]));
		if ($width == "") { $width = "180"; }
		if ($height == "") { $height = "300"; }
		$options["current_location_map_width"] = $width;
		$options["current_location_map_height"] = $height;
		$options["current_location_map_type"] = htmlspecialchars(trim($_POST["maps_type"]));
		$options["current_location_map_zoom"] = htmlspecialchars(trim($_POST["maps_zoom"]));
		
		//Save the settings into the WP Options Table.
		update_option("current_location_plugin", $options);
		$update = true;
	}
	
	if ($wp_version >= 2.8)
	{
		$cl_timezone = get_option('timezone_string');
		if ($cl_timezone == "")
		{
			$cl_timezone = __("Timezone not set. Go to Settings > General", 'current-location');
		}
	} else {
		$cl_timezone = __("Since you are using an older version of WordPress $wp_version, the timestamp output will be set to the server.", 'current-location');
	}

	$id = $options["current_location_userid"];
	$format_date = $options["current_location_date"];
	$temp_check = $options["current_location_temp_check"];
	$temp_location = $options["current_location_temp_location"];
	$temp_width = $options["current_location_map_width"];
	$temp_height = $options["current_location_map_height"];
	$temp_type = $options["current_location_map_type"];
	$temp_zoom = $options["current_location_map_zoom"];
	
	
	///Testing to see if Vacation is turned on. If so check it and fill in the info. If no, don't display anything.
	if ($temp_check == "Vac") { 
		$temp_check1 = "checked=\"checked\""; 
		$temp_location = $options["current_location_temp_location"]; 
	} else {
		$temp_check1 = "";
		$temp_location = "";
	}
	?>
	<h3 style="margin:auto 0px; margin-bottom:10px; margin-top:10px; width:100%; text-align:center;">Current Location <?php _e("Settings", 'current-location'); ?></h3>
  <?php printf(__("<ul><li>%s: Re-styled the options page. Easier and faster.</li><li>%s: Added the ability to edit a post without changing the Current Location Information.</li><li>%s: Added a TinyMCE Button to the Post/Page Editor.</li></ul>", 'current-location'), $CL_Version, "1.5.7", "1.5.6"); ?>
	<?php if ($update) { ?><p style="color:green;font-size:14px;"><?php _e("Settings Saved.", 'current-location'); ?></p><? } ?>
	<div class="clear"></div>
	<form id="cl_formOptions" name="cl_formOptions" method="post" action="">
	<table class="widefat" cellspacing="0"> 
		<thead>
			<tr>
				<th width="15%"><?php _e("Field", 'current-location'); ?></th>
				<th><?php _e("Value", 'current-location'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"><input type="submit" class="primary" name="CL_SAVE" id="CL_SAVE" value="<?php _e("Save Changes", 'current-location'); ?>" /></td>
			</tr>
			<tr>
				<th width="15%"><?php _e("Field", 'current-location'); ?></th>
				<th><?php _e("Value", 'current-location'); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<tr class="settings">
				<td><label for="user_id"><?php _e("User ID", 'current-location'); ?>:</label></td>
				<td><input name="user_id" type="text" id="user_id" size="25" value="<?php echo $id; ?>" /></td>
			</tr>
			<tr class="explain">
				<td>&nbsp;</td>
				<td><?php _e("Get your User ID from here", 'current-location');?>: <a target="_blank" title="<?php _e("Google Badge System", 'current-location'); ?>" href="http://www.google.com/latitude/apps/badge"><?php _e("Google Badge System", 'current-location'); ?></a>. <?php _e("Its is the ID number after", 'current-location'); ?> "api?user=". <br /><strong><?php _e("Example", 'current-location'); ?>:</strong> http://www.google.com/latitude/apps/badge/api?user=-123456789&type=json <?php _e("User ID is", 'current-location'); ?>: <strong>-123456789</strong></td>
			</tr>
			<tr class="settings">
				<td><label for="format_date"><?php _e("Date Format", 'current-location'); ?>:</label></td>
				<td><input name="format_date" type="text" id="format_date" size="25" value="<?php echo $format_date; ?>" /></td>
			</tr>
			<tr class="explain">
				<td>&nbsp;</td>
				<td><?php _e("Default Format is", 'current-location'); ?>: <strong>M j, Y g:i a</strong>. <?php _e("Use the", 'current-location'); ?> <a target="_blank" title="PHP Date" href="http://us3.php.net/manual/en/function.date.php">PHP Date</a> <?php _e("page to learn how to do different formating.", 'current-location'); ?><br /><br /><?php _e("Current Timezone", 'current-location'); ?>: <strong><a href="./options-general.php" title="<?php _e("Change the Timezone here.", 'current-location'); ?>"><?php echo $cl_timezone; ?></a></strong></td>
			</tr>
			<tr class="settings">
				<td><label for="temp_location"><?php _e("Vacation Text", 'current-location'); ?>:</label></td>
				<td><input style="position:relative;margin-top:0px;margin-right:5px;" name="temp_check" type="checkbox" id="temp_check" value="Vac" <?php echo $temp_check1; ?> /><input name="temp_location" type="text" id="temp_location" size="30" value="<?php echo $temp_location; ?>" /></td>
			</tr>
			<tr class="explain">
				<td>&nbsp;</td>
				<td><?php _e("Sometimes you don't want to update your location (example: vacation). Check this box below and manually enter a location or message.", 'current-location'); ?></td>
			</tr>
			<tr class="settings">
				<td><label for="maps_width"><?php _e("Map/Static Width", 'current-location'); ?>:</label></td>
				<td><input name="maps_width" type="text" id="maps_width" size="5" value="<?php echo $temp_width; ?>" /></td>
			</tr>
			<tr class="explain">
				<td>&nbsp;</td>
				<td><?php _e("Default Setting is", 'current-location'); ?>: <strong>180</strong></td>
			</tr>
			<tr class="settings">
				<td><label for="maps_height"><?php _e("Map/Static Height", 'current-location'); ?>:</label></td>
				<td><input name="maps_height" type="text" id="maps_height" size="5" value="<?php echo $temp_height; ?>" /></td>
			</tr>
			<tr class="explain">
				<td>&nbsp;</td>
				<td><?php _e("Default Setting is", 'current-location'); ?>: <strong>300</strong></td>
			</tr>
			<tr class="settings">
				<td><label for="maps_type"><?php _e("Map/Static Type", 'current-location'); ?>:</label></td>
				<td><select name="maps_type">
							<option<?php if ($temp_type == "roadmap") echo " selected=\"selected\""; ?> value="roadmap"><?php _e('Road', 'current-location'); ?></option>
							<option<?php if ($temp_type == "terrain") echo " selected=\"selected\""; ?> value="terrain"><?php _e('Terrain', 'current-location'); ?></option>
							<option<?php if ($temp_type == "hybrid") echo " selected=\"selected\""; ?> value="hybrid"><?php _e('Hybrid', 'current-location'); ?></option>
							<option<?php if ($temp_type == "satellite") echo " selected=\"selected\""; ?> value="satellite"><?php _e('Satellite', 'current-location'); ?></option>
						</select></td>
			</tr>
			<tr class="explain">
				<td>&nbsp;</td>
				<td><?php _e("Default Setting is", 'current-location'); ?>: <strong>Road</strong></td>
			</tr>
			<tr class="settings">
				<td><label for="maps_zoom"><?php _e('Map/Static Zoom Level', 'current-location'); ?>:</label></td>
				<td><select name="maps_zoom">
							<?php
							$lastTitle = "";
							for ($x = 1; $x < 22; $x++) {
								if ($x == 1)
									echo "<optgroup label=\"" . __("Continent", 'current-location') . "\">\n";
								if ($x == 4)
									echo "<optgroup label=\"" . __("Country", 'current-location') . "\">\n";
								if ($x == 7)
									echo "<optgroup label=\"" . __("Region", 'current-location') . "\">\n";
								if ($x == 10)
									echo "<optgroup label=\"" . __("City", 'current-location') . "\">\n";
								if ($x == 15)
									echo "<optgroup label=\"" . __("Very Close", 'current-location') . "\">\n";
							?>
							<option<?php if ($temp_zoom == $x) echo " selected=\"selected\""; ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
							<?php
								if ($x == 3)
									echo "</optgroup>\n";
								if ($x == 6)
									echo "</optgroup>\n";
								if ($x == 9)
									echo "</optgroup>\n";
								if ($x == 14)
									echo "</optgroup>\n";
								if ($x == 21)
									echo "</optgroup>\n";
							?>
							<?php 
							}
							?>
						</select></td>
			</tr>
			<tr class="explain">
				<td>&nbsp;</td>
				<td><?php _e('Default Setting is: <strong>10</strong>. Please note, not all Zoom Levels work with both Static Images and iframes. Find a zoom that fits you best.', 'current-location'); ?></td>
			</tr>
		</tbody>
	</table>
	</form>
  <script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.settings').hover(function() {
			jQuery(this).css('background','#bbd8e7');
			jQuery(this).next('.explain').css('background','#bbd8e7');
		},function() {
			jQuery(this).css('background','#FFFFFF');
			jQuery(this).next('.explain').css('background','#FFFFFF');
		});
		jQuery('.explain').hover(function() {
			jQuery(this).prev('.settings').css('background','#bbd8e7');
			jQuery(this).css('background','#bbd8e7');
		},function() {
			jQuery(this).prev('.settings').css('background','#FFFFFF');
			jQuery(this).css('background','#FFFFFF');
		});
	});
	</script>
	<?php
}
function current_location_page_examples() {
	global $CL_Version, $wp_version;
	?>
  <h3 style="width: 100%; text-align:center; margin:auto 0px; margin-top:15px; margin-bottom:10px;"><?php _e("Examples of how to use", 'current-location'); echo " Current Location $CL_Version"; ?></h3>
  <h3><?php _e('How to use the Plugin?', 'current-location');?></h3>
  <p><?php _e('Use this in your template to display your current location: <code>&lt;?php echo display_current_location(); ?&gt;</code>You can choose what to display or by default it will just display City Level. You can pass any of these and they will display in that order.', 'current-location'); ?></p>
  <ul>
    <li><strong>city</strong> :: <?php _e('Display Reverse Geocode (Usually City)', 'current-location'); ?></li>
    <li><strong>coords</strong> :: <?php _e('Display Coordinates', 'current-location'); ?></li>
    <li><strong>accuracy</strong> :: <?php _e('Display Accuracy In Meters', 'current-location'); ?></li>
    <li><strong>date</strong> :: <?php _e('Display Last Updated', 'current-location'); ?></li>
    <li><strong>map</strong> :: <?php _e('Display Map - Little Google Map', 'current-location'); ?></li>
    <li><strong>static</strong> :: <?php _e('Display Static Map - Without Picture Marker', 'current-location'); ?></li>
  </ul>
  <p><?php _e('Note: To display your current location in a post, use the Current Location Options in the new post screen.', 'current-location'); ?></p>
	<?php
}
function current_location_page_samples() {
	
	?>
	<h3><?php _e('Example of the Outputs', 'current-location'); ?></h3>
	<ul>
		<li><a href="./widgets.php" title="Current Location <?php _e('Widget Access', 'current-location'); ?>"><?php _e('Widget Access', 'current-location'); ?></a></li>
		<li><code>&lt;?php echo display_current_location(); ?&gt;</code> <?php _e('would output this: Nashville, TN, USA', 'current-location'); ?></li>
		<li><code>&lt;?php echo display_current_location("city", "accuracy"); ?&gt;</code> <?php _e('would output this: Nashville, TN, USA<br />2253 m', 'current-location'); ?></li>
		<li><code>&lt;?php echo display_current_location("city", "date", "coords", "accuracy"); ?&gt;</code> <?php _e('would output this: Nashville, TN, USA<br/>Jun 12, 2009 2:34 am<br />-86.682559, 36.085679<br />2253 m', 'current-location'); ?></li>
	</ul>
  <?php
}

function cl_dashboard_widget_function() {
	?>
  <ul style="list-style-type:disc;list-style-position:inherit; margin-left:15px;">
    <li>Current City: <?php echo display_current_location("city"); ?></li>
    <li>Last Updated: <?php echo display_current_location("date"); ?></li>
    <li>Coordinates: <?php echo display_current_location("coords"); ?></li>
    <li>Accuracy: <?php echo display_current_location("accuracy"); ?></li>
  </ul>
  <?php
}
function add_cl_dashboard_widgets() {
	global $CL_Version;
	wp_add_dashboard_widget('current_location_dashboard_widget', 'Current Location ' . $CL_Version, 'cl_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'add_cl_dashboard_widgets' );
?>