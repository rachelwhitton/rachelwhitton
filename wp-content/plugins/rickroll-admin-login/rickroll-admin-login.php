<?php

/**
 * Plugin Name: Wiggle Admin Login
 * Description: Redirects the user trying to log in with 'admin' as username, to an hour long video of Shaq and a cat wiggling on YouTube.
 * Author: Rachel Whitton
 * GitHub Plugin URI: rachelwhitton/rickroll-admin-login
 * Author URI: http://dev-rachelwhitton.pantheon.io/
 * Version: 1.3.4
 */

add_action( 'authenticate', 'rickroll_check_admin_login', 1, 2);

function rickroll_check_admin_login( $login, $username ) {
	if ( 'admin' == $username ) {
		wp_redirect( 'https://www.youtube.com/watch?v=hw1ERPIBbE8' );
		exit;
	}
}
