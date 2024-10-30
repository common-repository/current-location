<?php

function display_current_location() {
	$amount = func_num_args();
	
	//Stores the settings
	$settings = get_option("current_location_plugin");
	$is_Checked = $settings["current_location_temp_check"];
	$loc_userID = $settings["current_location_userid"];
	$loc_tempLoc = $settings["current_location_temp_location"];
	$loc_date_format = $settings["current_location_date"];
	$map_width = $settings["current_location_map_width"];
	$map_height = $settings["current_location_map_height"];
	$map_type = $settings["current_location_map_type"];
	$map_zoom = $settings["current_location_map_zoom"];
	
	if ($map_width == "") { $map_width = "180"; }
	if ($map_height == "") { $map_height = "300"; }
	
	//Checking for nulls
	if ($loc_userID == "") { $loc_userID = "Nothing"; }
	if ($loc_date_format == "") { $loc_date_format = "M j, Y g:i a"; }
	
	$feed = "http://www.google.com/latitude/apps/badge/api?user=". $loc_userID ."&type=json";

	//use cURL and grab the feed
	$ch = curl_init() or die ( curl_error() );
	curl_setopt( $ch, CURLOPT_URL, "$feed" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$data = curl_exec( $ch );
	curl_close( $ch );
	
	//decode the json file and store it.
	$loc_data = json_decode($data);
	$output = "";
	if(strtoupper($is_Checked) == "VAC")
	{
		//If user is on Vacation
		$output = $loc_tempLoc;
	} else {
		if ($loc_data->features[0] == NULL)
		{
			//Means the User_ID Failed to return the the correct file.
			//output Default
			$output = "<strong>" . __("User ID incorrect. Make sure you entered it correctly.", 'current-location') . "</strong>"; //You entered this id: $loc_userID
		} else {
			//User_ID returned the full file.
			//Store everything here.
			$new_Date = fixDate($loc_data->features[0]->properties->timeStamp);
			$temp_date = date($loc_date_format,$new_Date);
			$temp_reverse = $loc_data->features[0]->properties->reverseGeocode;
			$temp_accuracy = $loc_data->features[0]->properties->accuracyInMeters;
			$temp_coordsLat = $loc_data->features[0]->geometry->coordinates[1];
			$temp_coordsLong = $loc_data->features[0]->geometry->coordinates[0];
			
			if ($amount == 0) { 
				$output = $temp_reverse; 
			} else {
				for ($x = 0; $x < $amount; $x++)
				{
					//Loop | arguments to see which one are being used. 
					//Using strtoupper() to make all the arguments uppercase so it doesn't matter if they pass date|Date|DATE it will come out as DATE.					
					if (strtoupper(func_get_arg($x)) == "DATE") { $output .= $temp_date . "<br />"; }
					else if (strtoupper(func_get_arg($x)) == "CITY") { $output .= $temp_reverse . "<br />"; }		
					else if (strtoupper(func_get_arg($x)) == "ACCURACY") { $output .= $temp_accuracy . " m<br />"; }
					else if (strtoupper(func_get_arg($x)) == "COORDS") { $output .= $temp_coordsLat  . ", " . $temp_coordsLong  . "<br />"; }
					else if (strtoupper(func_get_arg($x)) == "MAP") {if ($map_zoom > 0 ) { $map_zoom = "". $map_zoom; } else { $map_zoom = "0"; } $output .= "<iframe src=\"http://www.google.com/latitude/apps/badge/api?user=$loc_userID&type=iframe&maptype=$map_type&z=$map_zoom\" width=\"$map_width\" height=\"$map_height\" frameborder=\"0\"></iframe><br />"; }
					else if (strtoupper(func_get_arg($x)) == "STATIC") {if ($map_zoom > 0 ) { $map_zoom = "". $map_zoom; } else { $map_zoom = "1"; } $output .= "<img src=\"http://maps.google.com/maps/api/staticmap?center=$temp_coordsLat,$temp_coordsLong&amp;maptype=$map_type&amp;zoom=$map_zoom&amp;size=".$map_width."x".$map_height."&amp;markers=color:red%7Clabel:M%7C$temp_coordsLat,$temp_coordsLong&amp;sensor=false\" height=\"$map_height\" width=\"$map_width\" /><br />";}
					else { /* Do Nothing */ }
					//http://maps.google.com/maps/api/staticmap?center=34.712434,-86.579948&size=180x300&zoom=10&markers=color:red|label:M|34.712434,-86.579948&sensor=false
				}
			}
		}
	}
	return $output;
}
function display_current_location_post($atts) {
	$tempArray = explode(',', $atts);
	$output = "";
	
	//Stores the settings
	$settings = get_option("current_location_plugin");
	$is_Checked = $settings["current_location_temp_check"];
	$loc_userID = $settings["current_location_userid"];
	$loc_tempLoc = $settings["current_location_temp_location"];
	$loc_date_format = $settings["current_location_date"];
	$map_width = $settings["current_location_map_width"];
	$map_height = $settings["current_location_map_height"];
	$map_type = $settings["current_location_map_type"];
	$map_zoom = $settings["current_location_map_zoom"];
	
	if ($map_width == "") { $map_width = "180"; }
	if ($map_height == "") { $map_height = "300"; }
	if ($map_zoom > 0 ) { $map_zoom = "". $map_zoom; } else { $map_zoom = ""; }
	
	//Checking for nulls
	if ($loc_userID == "") { $loc_userID = "Nothing"; }
	if ($loc_date_format == "") { $loc_date_format = "M j, Y g:i a"; }
	
	$feed = "http://www.google.com/latitude/apps/badge/api?user=". $loc_userID ."&type=json";

	//use cURL and grab the feed
	$ch = curl_init() or die ( curl_error() );
	curl_setopt( $ch, CURLOPT_URL, "$feed" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$data = curl_exec( $ch );
	curl_close( $ch );
	
	//decode the json file and store it.
	$loc_data = json_decode($data);
	
	if(strtoupper($is_Checked) == "VAC")
	{
		//If user is on Vacation
		echo $loc_tempLoc;
	} else {
		if ($loc_data->features[0] == NULL)
		{
			//Means the User_ID Failed to return the the correct file.
			//output Default
			$output = "<strong>" . __("User ID incorrect. Make sure you entered it correctly.", 'current-location') . "</strong>"; //You entered this id: $loc_userID
		} else {
			//User_ID returned the full file.
			//Store everything here.
			$new_Date = fixDate($loc_data->features[0]->properties->timeStamp);
			$temp_date = date($loc_date_format,$new_Date);
			$temp_reverse = $loc_data->features[0]->properties->reverseGeocode;
			$temp_accuracy = $loc_data->features[0]->properties->accuracyInMeters;
			$temp_coordsLat = $loc_data->features[0]->geometry->coordinates[1];
			$temp_coordsLong = $loc_data->features[0]->geometry->coordinates[0];
			
			if (count($tempArray) == 0) { 
				$output = $output . $temp_reverse; 
			} else {
				echo count($tempArray);
				foreach ($tempArray as $attr) {
					//Loop | arguments to see which one are being used. 
					//Using strtoupper() to make all the arguments uppercase so it doesn't matter if they pass date|Date|DATE it will come out as DATE.					
					if (strtoupper(trim($attr)) == "DATE") { $output = $output . $temp_date . "<br />"; }
					else if (strtoupper(trim($attr)) == "CITY") { $output = $output . $temp_reverse . "<br />"; }		
					else if (strtoupper(trim($attr)) == "ACCURACY") { $output = $output . $temp_accuracy . " m<br />"; }
					else if (strtoupper(trim($attr)) == "COORDS") { $output = $output . $temp_coordsLat  . ", " . $temp_coordsLong  . "<br />"; }
					else if (strtoupper(trim($attr)) == "MAP") { if ($map_zoom > 0 ) { $map_zoom = "". $map_zoom; } else { $map_zoom = "0"; } $output = $output . "<iframe src=\"http://www.google.com/latitude/apps/badge/api?user=$loc_userID&type=iframe&maptype=$map_type&z=$map_zoom\" width=\"$map_width\" height=\"$map_height\" frameborder=\"0\"></iframe><br />"; }
					else if (strtoupper(trim($attr)) == "STATIC") { if ($map_zoom > 0 ) { $map_zoom = "". $map_zoom; } else { $map_zoom = "1"; } $output = $output . "<img src=\"http://maps.google.com/maps/api/staticmap?center=$temp_coordsLat,$temp_coordsLong&amp;maptype=$map_type&amp;zoom=$map_zoom&amp;size=".$map_width."x".$map_height."&amp;markers=color:red%7Clabel:M%7C$temp_coordsLat,$temp_coordsLong&amp;sensor=false\" height=\"$map_height\" width=\"$map_width\" /><br />";}
					else { /* Do Nothing */ }
					//http://maps.google.com/maps/api/staticmap?center=34.712434,-86.579948&size=180x300&zoom=10&markers=color:red|label:M|34.712434,-86.579948&sensor=false
				}
			}
		}
	}
	return $output;
}
?>