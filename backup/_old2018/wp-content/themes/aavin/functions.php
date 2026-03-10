<?php
/**
 * Aavin functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Aavin
 * @since Aavin 1.0
 */

/**
 * Aavin only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'Aavin_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own Aavin_setup() function to override in a child theme.
 *
 * @since Aavin 1.0
 */
function Aavin_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Aavin, use a find and replace
	 * to change 'Aavin' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'Aavin', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 *
	 *  @since Aavin 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
		) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'Aavin' ),
		'social'  => __( 'Social Links Menu', 'Aavin' ),
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
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
		) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', Aavin_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // Aavin_setup
add_action( 'after_setup_theme', 'Aavin_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Aavin 1.0
 */
function Aavin_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'Aavin_content_width', 840 );
}
add_action( 'after_setup_theme', 'Aavin_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Aavin 1.0
 */
function Aavin_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'Aavin' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'Aavin' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
		) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'Aavin' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'Aavin' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
		) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'Aavin' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'Aavin' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
		) );
	
	//header widget
	register_sidebar( array(
		'name'          => __( 'Partner Portal', 'Aavin' ),
		'id'            => 'partner-portal',
		'description'   => __( 'Appears at the header of the content Home pages.', 'Aavin' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '',
		'after_title'   => '',
		) );
	//FINANCING INQUIRY
	register_sidebar( array(
		'name'          => __( 'Financing Inquiry', 'Aavin' ),
		'id'            => 'financing',
		'description'   => __( 'Appears at the content Home pages.', 'Aavin' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '',
		'after_title'   => '',
		) );
	//footer
	register_sidebar( array(
		'name'          => __( 'Footer Content', 'Aavin' ),
		'id'            => 'footer',
		'description'   => __( 'Appears at the footer', 'Aavin' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '',
		'after_title'   => '',
		) );
}
add_action( 'widgets_init', 'Aavin_widgets_init' );

if ( ! function_exists( 'Aavin_fonts_url' ) ) :
/**
 * Register Google fonts for Aavin.
 *
 * Create your own Aavin_fonts_url() function to override in a child theme.
 *
 * @since Aavin 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function Aavin_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'Aavin' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'Aavin' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'Aavin' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
			), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Aavin 1.0
 */
function Aavin_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'Aavin_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Aavin 1.0
 */
function Aavin_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'Aavin-fonts', Aavin_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'Aavin-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'Aavin-ie', get_template_directory_uri() . '/css/ie.css', array( 'Aavin-style' ), '20160412' );
	wp_style_add_data( 'Aavin-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'Aavin-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'Aavin-style' ), '20160412' );
	wp_style_add_data( 'Aavin-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'Aavin-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'Aavin-style' ), '20160412' );
	wp_style_add_data( 'Aavin-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'Aavin-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'Aavin-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'Aavin-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160412', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'Aavin-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160412' );
	}

	wp_enqueue_script( 'Aavin-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160412', true );

	wp_localize_script( 'Aavin-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'Aavin' ),
		'collapse' => __( 'collapse child menu', 'Aavin' ),
		) );
}
add_action( 'wp_enqueue_scripts', 'Aavin_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Aavin 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function Aavin_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'Aavin_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Aavin 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function Aavin_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Aavin 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function Aavin_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'Aavin_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Aavin 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function Aavin_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'Aavin_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Aavin 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function Aavin_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'Aavin_widget_tag_cloud_args' );
//slider custom post
register_post_type('slider', array(	'label' => 'slider','description' => 'slider','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'exclude_from_search' => false,'supports' => array('title','thumbnail','page-attributes','editor'),'taxonomies' => array('category',),'labels' => array (
	'name' => 'Slider',
	'singular_name' => 'Slider',
	'menu_name' => 'Slider',
	'add_new' => 'Add Slider',

	'add_new_item' => 'Add Slider',
	'edit' => 'Edit Slider',
	'edit_item' => 'Edit Slider',
	'new_item' => 'New Slider',
	'view' => 'View Slider',
	'view_item' => 'View Slider',
	'taxonomies' => array('category'),
	'search_items' => 'Search Slider',
	'not_found' => 'No Slider Found',
	'not_found_in_trash' => 'No Slider Found in Trash',
	'parent' => 'Parent Slider',
	),) );

//news board custom post
register_post_type('news', array(	'label' => 'news','description' => 'news','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'exclude_from_search' => false,'supports' => array('title','thumbnail','page-attributes','editor'),'taxonomies' => array('category',),'labels' => array (
	'name' => 'News',
	'singular_name' => 'News',
	'menu_name' => 'News',
	'add_new' => 'Add News',

	'add_new_item' => 'Add News',
	'edit' => 'Edit News',
	'edit_item' => 'Edit News',
	'new_item' => 'New News',
	'view' => 'View News',
	'view_item' => 'View News',
	'taxonomies' => array('category'),
	'search_items' => 'Search News',
	'not_found' => 'No News Found',
	'not_found_in_trash' => 'No News Found in Trash',
	'parent' => 'Parent News',
	),) );

//resources custom post
register_post_type('resources', array(	'label' => 'resources','description' => 'resources','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'exclude_from_search' => false,'supports' => array('title','thumbnail','page-attributes','editor'),'taxonomies' => array('category',),'labels' => array (
	'name' => 'Resources',
	'singular_name' => 'Resources',
	'menu_name' => 'Resources',
	'add_new' => 'Add Resources',

	'add_new_item' => 'Add Resources',
	'edit' => 'Edit Resources',
	'edit_item' => 'Edit Resources',
	'new_item' => 'New Resources',
	'view' => 'View Resources',
	'view_item' => 'View Resources',
	'taxonomies' => array('category'),
	'search_items' => 'Search Resources',
	'not_found' => 'No Resources Found',
	'not_found_in_trash' => 'No Resources Found in Trash',
	'parent' => 'Parent Resources',
	),) );



/* Function which remove Plugin Update Notices – photo-gallery */

/*function disable_plugin_updates( $value ) {
   unset( $value->response['photo-gallery/yhoto-galler.phpp'] );
   return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );*/

/* Function which remove Plugin Update Notices – robo-gallery */
function disable_plugin_updates( $value ) {
	//unset( $value->response['robo-gallery/robogallery.php'] );
	return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

/* Function which rewrite the url */
add_action( 'init', 'add_author_rules' );
function add_author_rules() { 
// add_rewrite_rule("portfolio/([0-9]{4})/?",'index.php?post_type=gallery=$matches[1]','top');
}

function objectToArray($obj)
{
	if (is_object($obj)):
		$object = get_object_vars($obj); 
	endif;

         return array_map('objectToArray', $object); // return the object, converted in array.
}



add_action( 'init', 'ourteam' );
function ourteam() {
     	$labels = array(
     		'name'               => 'Team Members',
     		'singular_name'      => 'Team Members',
     		'add_new'            => 'Add Team Member',
     		'add_new_item'       => 'Add New Team Member',
     		'edit_item'          => 'Edit Team Member' ,
     		'new_item'           => 'New Team Member',
     		'all_items'          => 'All Team Members',
     		'view_item'          => 'View Team Member',
     		'search_items'       => 'Search Team Member',
     		'not_found'          => 'No Team Member found',
     		'not_found_in_trash' => 'No Team Member in Trash',
     		'parent_item_colon'  => '',
     		'menu_name'          => 'Our Team'
     		);

     	$args = array(
     		'labels'             => $labels,
     		'public'             => true,
     		'publicly_queryable' => true,
     		'show_ui'            => true,
     		'show_in_menu'       => true,
     		'query_var'          => true,
     		'rewrite'            => array( 'slug' => '' ),
     		'capability_type'    => 'post',
     		'has_archive'        => true,
     		'hierarchical'       => false,
     		'menu_position'      => null,
     		'supports'           => array( 'title', 'editor', 'thumbnail' )
     		);

     	register_post_type( 'ourteam', $args );

}

add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );

function wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url = get_edit_profile_url( $user_id );

	if ( 0 != $user_id ) {
		/* Add the "My Account" menu */
		$avatar = get_avatar( $user_id, 28 );
		$howdy = sprintf( __('Welcome, %1$s'), $current_user->display_name );
		$class = empty( $avatar ) ? '' : 'with-avatar';

		$wp_admin_bar->add_menu( array(
			'id' => 'my-account',
			'parent' => 'top-secondary',
			'title' => $howdy . $avatar,
			'href' => $profile_url,
			'meta' => array(
				'class' => $class,
			),
		) );
	}
}

// Function to change email address

function wpb_sender_email( $original_email_address ) {
    return 'gmilroy@aavin.com';
}

// Function to change sender name
function wpb_sender_name( $original_email_from ) {
	return 'Gina Milroy';
}

// Hooking up our functions to WordPress filters 
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
