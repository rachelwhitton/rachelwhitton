<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );

if (window.location.hostname == 'www.rachelwhitton.com' ||
    window.location.hostname == 'live-rachelwhitton.pantheon.io' ||
    window.location.protocol == 'http:'
) {
    redirect = 'https://rachelwhitton.com' + window.location.pathname
    // bounce bounce
    window.location = redirect
}
if (window.location.hostname == 'www.staging.rachelwhitton.com' ||
    window.location.hostname == 'staging.rachelwhitton.com' ||
    window.location.hostname == 'test-rachelwhitton.pantheon.io' ||
    window.location.protocol == 'http:'
) {
    redirect = 'https://staging.rachelwhitton.com' + window.location.pathname
    // bounce bounce
    window.location = redirect
}
if (window.location.hostname == 'www.sandbox.rachelwhitton.com' ||
    window.location.hostname == 'dev-rachelwhitton.pantheon.io' ||
    window.location.protocol == 'http:'
) {
    redirect = 'https://sandbox.rachelwhitton.com' + window.location.pathname
    // bounce bounce
    window.location = redirect
}
