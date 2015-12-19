<?php
/**
 * Studio Theme Customizer
 *
 * @package Studio
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function studio_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	// Theme doesn't add a tagline.
	$wp_customize->remove_control('blogdescription');
}
add_action( 'customize_register', 'studio_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function studio_customize_preview_js() {
	wp_enqueue_script( 'studio_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '1.0.0', true );
}
add_action( 'customize_preview_init', 'studio_customize_preview_js' );
