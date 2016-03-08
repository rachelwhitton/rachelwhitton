<?php
/**
 * Plugin Name: Test Temp Dir
 * Description: Creates a single temporary file and deletes it to test WP_TEMP_DIR
 * Author: Rachel Whitton
 * GitHub Plugin URI: rachelwhitton/test-temp-dir
 * Version: 0.0.1
 */

add_action( 'authenticate', 'test_tmp_dir_admin_login', 1, 2);
function test_tmp_dir_admin_login( $login, $username ) {
	if ( 'admin' == $username ) {
		$tmpfname = tempnam("/tmp", "FOO");
		$handle = fopen($tmpfname, "w");
		fwrite($handle, "writing to tempfile");

	}
}
