<?php
/*
 * Set up the content width value based on the theme's design.
 */
if ( ! function_exists( 'advent_setup' ) ) :
function advent_setup() {
	
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 870;
	}
	
	// Make advent theme available for translation.
	load_theme_textdomain( 'advent', get_template_directory() . '/languages' );
	
        // This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', advent_font_url() ) );
	
        // Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	
        // Featured image
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'advent-full-width', 1038, 576, true );
	add_image_size('headertop-logo', 155, 155, true);

	
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'advent' ),
	) );
	
	// Switch default core markup for search form, comment form, and commen, to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
	) );
	
	// Add support for featured content.
	add_theme_support('featured-content', array(
	   'featured_content_filter' => 'advent_get_featured_posts',
	   'max_posts' => 6,
	));
	
	add_theme_support( 'custom-header', apply_filters( 'advent_custom_header_args', array(
	'uploads'       => true,
	'flex-height'   => true,
	'default-text-color' => '#000',
	'header-text' => true,
	'height' => '120',
	'width'  => '1260'
 	) ) );
	add_theme_support( 'custom-background', apply_filters( 'advent_custom_background_args', array(
	'default-color' => 'f5f5f5',
	) ) );
	
	// This theme uses its own gallery styles.       
	add_filter('use_default_gallery_style', '__return_false'); 
	add_editor_style('css/editor-style.css');	
	
	
        
	
        
	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'advent_get_featured_posts',
		'max_posts' => 6,
	) );
        
	// This theme uses its own gallery styles.
	add_filter('use_default_gallery_style', '__return_false' );
        
    /* slug setup */	
    add_theme_support( 'title-tag' );

    /* height width automaticly adjust for home slider */
    add_image_size( 'advent-home-thumbnail-image', 250, 180, true );
        
}
endif; // advent_setup
add_action( 'after_setup_theme', 'advent_setup' );

/**
 * Register OpenSans Google font for advent.
 */
function advent_font_url() {
	$advent_font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by OpenSans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'OpenSans font: on or off', 'advent' ) ) {
		$advent_font_url = add_query_arg( 'family', urlencode( 'OpenSans:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css?family=Open+Sans" );
	}
	return $advent_font_url;
}

/* top header background image on wp_head hook */
add_action('wp_head','advent_header_bg');
function advent_header_bg()
{
	$advent_options = get_option('advent_theme_options');
	if (!empty($advent_options['headertop-bg'])) 
	{
		$advent_header_bg_img="<style> .header_bg { background :url('".$advent_options['headertop-bg']."'); } </style>";
		echo $advent_header_bg_img;
	}	
}


/* Set size of characher in excerpt */
function advent_excerpt_length( $length ) {
	return 63;
}
add_filter( 'excerpt_length', 'advent_excerpt_length', 999 );

/* readmore button if more content */
function advent_excerpt_more() {
   return '...</p><div class="read-more"><a href="' . get_permalink() . '" class="read-button">'.__('Read more','advent').'</a></div>';

}
add_filter("excerpt_more", "advent_excerpt_more");


/*
 * Comments placeholder function
 * 
**/
add_filter( 'comment_form_default_fields', 'advent_comment_placeholders' );
function advent_comment_placeholders( $fields )
{
	$fields['author'] = str_replace(
		'<input',
		'<input placeholder="'
		. _x(
		'Name *',
		'comment form placeholder',
		'advent'
		)
		. '" required',
		
	$fields['author']
	);
	$fields['email'] = str_replace(
		'<input',
		'<input id="email" name="email" type="text" placeholder="'
		. _x(
		'Email Id *',
		'comment form placeholder',
		'advent'
		)
		. '" required',
	$fields['email']
	);
	$fields['url'] = str_replace(
		'<input',
		'<input id="url" name="url" type="text" placeholder="'
		. _x(
		'Website URl',
		'comment form placeholder',
		'advent'
		)
		. '" required',
	$fields['url']
	);
	return $fields;
}
add_filter( 'comment_form_defaults', 'advent_textarea_insert' );
	function advent_textarea_insert( $fields )
	{
		$fields['comment_field'] = str_replace(
			'<textarea',
			'<textarea id="comment" aria-required="true" rows="8" cols="45" name="comment" placeholder="'
			. _x(
			'Comment',
			'comment form placeholder',
			'advent'
			)
		. '" ',
		$fields['comment_field']
		);
	return $fields;
	}

function advent_get_image_id($advent_image_url) {
	global $wpdb;
	$advent_attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $advent_image_url )); 
    return $advent_attachment[0]; 
}

/*** Enqueue css and js files ***/
require_once('functions/enqueue-files.php');
/*** Theme Option ***/
require_once('theme-options/themeoptions.php');
/*** Theme Default Setup ***/
require_once('functions/theme-default-setup.php');
/*** Breadcrumbs ***/
require_once('functions/breadcrumbs.php');
/*** Custom Header ***/
require_once('functions/custom-header.php');



