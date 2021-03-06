=== Genesis Featured Image ===

Contributors: ibonazkoitia
Author URI: http://www.ibonazkoitia.com
Tags: featured image, featured images, genesis, studiopress, genesis framework
Requires at least: 3.8
Tested up to: 4.9.8
Stable tag: 1.2.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

This plugin works within the Genesis Framework, and lets you display a featured image.

== Description ==

This plugins gives you the option to add a featured image before or after your header with a simple selection and save of it.

_Note: although this plugin requires the [Genesis Framework by StudioPress](http://studiopress.com/) or child themes, it is not an official plugin for this framework and is neither endorsed nor supported by StudioPress._

Files structures and concept are based in [Display Featured Image for Genesis - by Robin Cornett](http://robincornett.com/plugins/display-featured-image-genesis/)

= Future Options =

* different styles options
* and more!

== Installation ==

1. Upload the entire `genesis-featured-image` folder to your `/wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit the Genesis - Featured Image to select the image you want to feature.

== Frequently Asked Questions ==

= Does this work with any Genesis child theme? =

Technically, it should work, even older (XHTML) themes. However, depending on other factors such as the individual theme's styling and layout, the output may be unexpected, and require some tweaking.

= How can I change the styles? =

In this version style options are not added yet, but the image it's in a div and you can style it:

= Inside Wrap =
	
	<div class="wrap">
		<div id="gfi-featured-image">
			<img>
		</div>
	</div>

= Full Width =

	<div id="gfi-featured-image" class="gfi-full-width">
		<img>
	</div>

== Screenshots ==
1. **Genesis Featured Image in Action** - Screenshot of a page using the Genesis Featured Image
2. **Settings Page** - Screenshot of the plugin settings page Genesis -> Featured Image

== Changelog ==

= 1.2.0 - 25 Oct 2018 =
* Test with latest WordPress version available (4.9.8)
* Test with the latest version of Genesis Framework (2.6.1)
* Change css style for the image from full with to auto image size
* Update plugin developer info and contact info

= 1.1.0 - 30 Jun 2015 =
* Add Full Width Option
* Add Hook Priority Option
* Add Before/After Header selection Option
* Remove index.php
* Improve code standars

= 1.0.1 - 6 Jun 2015 =
* Minor tweaks to readme file
* Correct Changelog output
* Add thank you message to the admin footer

= 1.0.0 - 5 Jun 2015 =
* Initial release