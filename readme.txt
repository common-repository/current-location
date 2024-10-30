=== Current Location ===
Contributors: justindgivens
Author: Justin D. Givens
Author URI: http://plugins.justingivens.com/?aid=current-location-plugin
Plugin URI: http://plugins.justingivens.com/?pid=current-location-plugin
Tags: gps, location, google, latitude, json
Requires at least: 2.9
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P65ZBRE95LYKG
Tested up to: 3.3
Stable tag: 1.5.9

Gets your current location from Google Badge System. Use <?php echo display_current_location(); ?> to display your current location.

== Description ==

The plugin uses the Google Badge System and pulls the JSON file. The plugin then parses through the json file and displays your current location. You need to have a Google Account and signed into Google Latitude on your computer/mobile device. The idea behind this plugin is have my blog display my current location without my editing anything. 

This plugin requires php5 or greater.

== Upgrade Notice ==
* When upgrading your current-location edits will still work. But I've added the widget support, and [current-location] tag to post as well.
* Also added the dashboard widget.

== Installation ==

1. Copy the folder 'current-location' into the directory 'wp-content/plugins/'.

2. Activate the plugin.

3. Find the Current Location settings button in your side of the 'wp-admin' panel.

4. Update all settings.

5. Add '&lt;?php echo display&#95;current&#95;location(); ?&gt;' to your template. OR Add [current-location] to your posts. OR Add the Current Location Widget.

== Frequently Asked Questions ==

If your question isn't here, ask your own question at [the Wordpress.org forums](http://wordpress.org/tags/current-location).

= Output says this "User ID incorrect. You entered this id: Nothing" =
You need to visit the Current Location settings page and update the Google Badge UserID.

= On the settings page you see this note: "Since you are using an older version of WordPress, the timestamp output will be set to the server." =
All that means is your not using Wordpress 2.8, which is fine, but the new TimeZone feature in 2.8 will fix the timestamp to your local blog timezone instead of your server timezone. Example: My server is located in the EST timezone but I want my blog on the CST. This will fix the timestamps for CST not EST.

== Changelog ==

= 1.5.9 =
* Tested again 3.3 Release.
* Added a Dashboard Widget so you can see what your current location is when you login.

= 1.5.8 =
* Re-styled the Admin Page. 

= 1.5.7 =
* Updated the Post/Page Meta Information to be able to keep previously stored information. So if you edit a post, it doesn't automatically update your Current Location. It gives you an option to update it.
* Fixed Bug on the Page Meta Information.

= 1.5.6 =
* Updated all i18n Calls with the domain in the gettext calls. I missed this early. Sorry.

= 1.5.5 =
* Fixed some i18n issues.
* Added the TinyMCE button to the Post/Page Screen.
* Fixed a bug with the Post/Pages Meta Tags.
* Ready for i18n!

= 1.5.4 =
* Minor updates. Renamed some variables that might be used by other people.
* Updated all the stored and echos variables to be ready for I18n.

= 1.5.3 =
* Removed the userid from the error message so someone wouldn't steal it from the output. 
* Changed the Map Zoom to Numbers instead of words. 
* Updated the Config page to show the correct usage for a template add.
* Also fixed a bug with the Static Maps (Thanks mnbvcx)

= 1.5.2 =
* Post or Pages wouldn't display any content if you used the words current-location. Now I make sure to look for '[current-location' first then try and switch it with the post meta information.

= 1.5.1 =
* Minor change with display of the post/page meta data.

= 1.5 =
* Added Widget Support.
* Added [current-location] shortcode to the post page. 
* Added Post Meta Data to work with the [current-location] shortcode.
* Added Static Maps as an option.

= 1.4 =
* Didn't release because I made major updates to the plugin so I went to 1.5 before releasing this.
* Works with WP 2.9.2
* Added new function to store your current location in the meta table. You need to choose to store your location on the post and it will append it to the end of the article.
* Works with both pages and post.
* Currently adds '&lt;p&gt;Posted from: Nashville, TN, USA&lt;/p&gt;' to the end of each post that has the meta data stored.

= 1.3 =
* Fixed spelling mistakes and updating all the correct files this times :)
* Updated readme, index and screenshots.

= 1.2 =
* Still learning Subversion and upload to early. Sorry about this.

= 1.1 =
*  Removed all old comments that had my default information stored.

= 1.0 =
* Initial upload.
