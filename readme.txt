=== Genesis Featured Image ===

Contributors: ibonazkoitia
Author URI: http://www.kreatidos.com
Tags: featured image, featured images, genesis, studiopress, genesis framework
Requires at least: 3.8
Tested up to: 4.2.2
Stable tag: 1.0.1
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

This plugin works within the Genesis Framework, to display featured images.

== Description ==

This plugins gives you the option to add a featured image under your header with a simple selection and save of it.

_Note: although this plugin requires the [Genesis Framework by StudioPress](http://studiopress.com/) or child themes, it is not an official plugin for this framework and is neither endorsed nor supported by StudioPress._

= Future Options =

* different styles options
* select the priority of the hook
* select the final position, before/after the header
* and more!

== Installation ==

1. Upload the entire `genesis-featured-image` folder to your `/wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit the Genesis - Featured Image to select the image you want to feature.

== Frequently Asked Questions ==

= Does this work with any Genesis child theme? =

Technically, it should work, even older (XHTML) themes. However, depending on other factors such as the individual theme's styling and layout, the output may be unexpected, and require some tweaking.

= How can I change the styles? =

In this version style options are not added yet, but the image is in a div and you can style it:

	<div id="gfi-featured-image">
		<img>
	</div>


== Screenshots ==
1. **Genesis Featured Image in Action** - Screenshot of a page using the Genesis Featured Image
2. **Settings Page** - Screenshot of the plugin settings page Genesis -> Featured Image

== Changelog ==

= 1.0.1 - 6 Jun 2015 =
* Minor tweaks to readme file
* Correct Changelog output
* Add thank you message to the admin footer

= 1.0.0 - 5 Jun 2015 =
* Initial release