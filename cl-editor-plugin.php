<?php

function current_location_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_current_location_tinymce_plugin");
     add_filter('mce_buttons', 'register_current_location_button');
   }
}
 
function register_current_location_button($buttons) {
   array_push($buttons, "|", "current_location");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_current_location_tinymce_plugin($plugin_array) {
   $plugin_array['current_location'] = WP_PLUGIN_URL . '/current-location/cl-editor-plugin.js';
   return $plugin_array;
}
 
// init process for button control
add_action('init', 'current_location_addbuttons');


?>