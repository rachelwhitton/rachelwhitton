=== LH HSTS ===
Contributors:      shawfactor
Donate link: 	   https://lhero.org/plugins/lh-hsts/
Tags:              HSTS, https, ssl, security, redirect
Requires at least: 3.0
Tested up to:      4.7
Stable tag:        trunk
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

HSTS is HTTP Strict Transport Security, a means to enforce using SSL even if the user accesses the site through HTTP and not HTTPS.

== Description ==

This plugin send the proper headers for full ssl security. For more information on what this is and why it is important visit: http://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security

The options are preset to enable browsers to preload the HSTS directive but can be overwritten by filters which are clearly documented in the code.

**Did you find this plugin helpful? Please consider [writing a review](https://wordpress.org/support/view/plugin-reviews/lh-hsts).**

== Installation ==

1. Upload the entire `lh-hsts` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= How do I change the behaviour of this plugin? =

Through filters, all of which are commented in the code and will be documented below.


== Changelog ==

= 1.00 - February 28, 2016 =
* Initial release

= 1.10 - March 28, 2016 =
* Use correct domain

= 1.11 - April 02, 2017 =
* Added class exists check
