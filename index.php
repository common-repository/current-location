<?php
/*
Plugin Name: Current Location
Plugin URI: http://plugins.justingivens.com/?pid=Current-Location
Description: Gets your current location from <a href="http://www.google.com/latitude/apps/badge">Google Badge System</a>. Use <code>&lt;?php echo display_current_location(); ?&gt;</code> to display your current location.
Version: 1.5.9
Author: Justin D. Givens
Author URI: http://plugins.justingivens.com/?aid=Current-Location
Copyright 2011 Justin D. Givens

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
$CL_Version = "1.5.9";
DEFINE('CL_VERSION', "1.5.9");

load_plugin_textdomain('current-location', 'wp-content/plugins/current-location/locale');

include('cl-function.php');
include('cl-post.php');
include('cl-widget.php');
include('cl-editor-plugin.php');
include('cl-options.php');

function fixDate($incoming_timeStamp) {
	global $wp_version;
	//Function to take in the GMT and fix the time.
	//Set the timezone default if you are using WP 2.8 or higher.
	//If you don't have 2.8 or higher then it unix timestamp will return the server time. Not the Blog local time. 
	if ($wp_version >= 2.8)
	{
		date_default_timezone_set(get_option('timezone_string'));
	}
	return $incoming_timeStamp;
}
function cl_menu() {
  add_menu_page("Current Location", "Current Location", "activate_plugins", "current-location", "current_location_page_output", WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__)) . 'icon.png' );
}
add_action('admin_menu','cl_menu');
?>