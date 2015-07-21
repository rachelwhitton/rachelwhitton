<?php
/**
 * Studio functions and definitions
 *
 * @package Studio
 */


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 924; /* pixels */
}

if ( ! function_exists( 'studio_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function studio_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Studio, use a find and replace
	 * to change 'studio' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'studio', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	
	// Add tyles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', studio_fonts_url() ) );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 800, 450, true );
	add_image_size( 'studio-single', '800', '450', true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'studio' ),
		'social'  => esc_html__( 'Social Menu', 'studio' ),		
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'studio_custom_background_args', array(
	    'default-attachment'	=> 'fixed',
		'default-color'			=> '',
		'default-image'			=> get_template_directory_uri() . '/images/default-image.jpg',
		'default-position-x'	=> 'center',
	) ) );
}
endif; // studio_setup
add_action( 'after_setup_theme', 'studio_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function studio_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'studio_content_width', 800 );
}
add_action( 'after_setup_theme', 'studio_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function studio_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget (left)', 'studio' ),
		'id'            => 'footer-widget-left',
		'description'   => 'Left footer widget',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget (right)', 'studio' ),
		'id'            => 'footer-widget-right',
		'description'   => 'Right footer widget',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'studio_widgets_init' );

/**
 * Google fonts.
 */
function studio_fonts_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by chosen font(s), translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Google font: on or off', 'studio' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Montserrat|Bowlby One|Quattrocento Sans:400,400italic,700italic,700&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 */
function studio_admin_fonts() {
	wp_enqueue_style( 'studio-font', studio_fonts_url(), array(), '1.0.0' );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'studio_admin_fonts' );

/**
 * Enqueue scripts and styles.
 */
function studio_scripts() {
	wp_enqueue_style( 'studio-style', get_stylesheet_uri(), array(), '1.0.0' );
	
	wp_enqueue_style( 'studio-fonts', studio_fonts_url(), array(), '1.0.0' );

	wp_enqueue_style( 'studio-icons', get_template_directory_uri() . '/css/typicons.css', array(), '1.0.0' );

	wp_enqueue_script( 'studio-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true );
	
	wp_enqueue_script( 'studio-helpers', get_template_directory_uri() . '/js/helpers.js', array( 'jquery' ), '1.0.0', true );

	wp_enqueue_script( 'studio-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '1.0.0', true );
	
	// Localize script (only few lines in helpers.js)
    wp_localize_script( 'studio-helpers', 'placeholder', array(  
 	    'author'   => __( 'Name', 'studio' ), 
 	    'email'    => __( 'Email', 'studio' ),
		'url'      => __( 'URL', 'studio' ),
		'comment'  => __( 'Comment', 'studio' ) 
 	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'studio_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';