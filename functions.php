<?php
/**
 * Squire functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Squire
 */

if ( ! function_exists( 'squire_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function squire_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Squire, use a find and replace
		 * to change 'squire' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'squire', get_template_directory() . '/languages' );

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
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'squire-featured-image', 2000, 1200, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary-menu'      => esc_html__( 'Primary', 'squire' ),
				'social-links-menu' => esc_html__( 'Social Links', 'squire' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background', apply_filters(
				'squire_custom_background_args', array(
					'default-color' => 'e6e6e6',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo', array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		add_editor_style(
			array(
				'assets/css/main.css',
				'assets/css/editor-style.css',
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'squire_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function squire_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'squire_content_width', 640 );
}

add_action( 'after_setup_theme', 'squire_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function squire_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'squire' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'squire' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'widgets_init', 'squire_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function squire_scripts() {
	$suffix = is_rtl() ? '-rtl' : '';

	// Load Animate.css, used in the main stylesheet.
	wp_enqueue_style( 'squire-animate-css', get_theme_file_uri( '/assets/css/animate' . $suffix . '.css' ), array(), null );

	// Load Font Awesome, used in the main stylesheet.
	wp_enqueue_style( 'squire-font-awesome', get_theme_file_uri( '/assets/css/font-awesome' . $suffix . '.css' ), array(), null );

	// Load our main stylesheet.
	wp_enqueue_style( 'squire-main', get_theme_file_uri( '/assets/css/main' . $suffix . '.css' ), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'squire-style', get_stylesheet_uri(), array( 'squire-main' ), null );

	// Helps with accessibility for keyboard only users.
	wp_enqueue_script( 'squire-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), null, true );

	// Load our Foundation scripts.
	wp_enqueue_script( 'squire-foundation', get_theme_file_uri( '/assets/js/foundation.js' ), array( 'jquery' ), null, true );

	// Load our theme scripts.
	wp_enqueue_script( 'squire-theme', get_theme_file_uri( '/assets/js/theme.js' ), array( 'jquery' ), null, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'squire_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom menu walker.
 */
require get_template_directory() . '/inc/class-menu-dropdown-walker.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
