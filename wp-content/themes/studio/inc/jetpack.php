<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package Studio
 */

/**
 * Jetpack support
 */
function studio_jetpack_setup() {
    /**
     * Add theme support for infinite scroll.
     */
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'studio_infinite_scroll_render',
		'footer'    => 'page',
	) );	
    /**
     * Add theme support for responsive videos.
     */
    add_theme_support( 'jetpack-responsive-videos' );	
} // end function studio_jetpack_setup
add_action( 'after_setup_theme', 'studio_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function studio_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function studio_infinite_scroll_render
