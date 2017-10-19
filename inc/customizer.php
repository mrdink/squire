<?php
/**
 * Squire Customizer functionality
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Squire 1.0
 */

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Squire 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function squire_customize_register( $wp_customize ) {
	$color_scheme = squire_get_color_scheme();

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname', array(
				'selector' => '.site-title a',
				'container_inclusive' => false,
				'render_callback' => 'squire_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription', array(
				'selector' => '.site-description',
				'container_inclusive' => false,
				'render_callback' => 'squire_customize_partial_blogdescription',
			)
		);
	}

	// Add color scheme setting and control.
	$wp_customize->add_setting(
		'color_scheme', array(
			'default'           => 'default',
			'sanitize_callback' => 'squire_sanitize_color_scheme',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'color_scheme', array(
			'label'    => __( 'Base Color Scheme', 'squire' ),
			'section'  => 'colors',
			'type'     => 'select',
			'choices'  => squire_get_color_scheme_choices(),
			'priority' => 1,
		)
	);

	// Add custom header and sidebar text color setting and control.
	$wp_customize->add_setting(
		'sidebar_textcolor', array(
			'default'           => $color_scheme[4],
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'sidebar_textcolor', array(
				'label'       => __( 'Header and Sidebar Text Color', 'squire' ),
				'description' => __( 'Applied to the header on small screens and the sidebar on wide screens.', 'squire' ),
				'section'     => 'colors',
			)
		)
	);

	// Remove the core header textcolor control, as it shares the sidebar text color.
	$wp_customize->remove_control( 'header_textcolor' );

	// Add custom header and sidebar background color setting and control.
	$wp_customize->add_setting(
		'header_background_color', array(
			'default'           => $color_scheme[1],
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'header_background_color', array(
				'label'       => __( 'Header and Sidebar Background Color', 'squire' ),
				'description' => __( 'Applied to the header on small screens and the sidebar on wide screens.', 'squire' ),
				'section'     => 'colors',
			)
		)
	);

	// Add an additional description to the header image section.
	$wp_customize->get_section( 'header_image' )->description = __( 'Applied to the header on small screens and the sidebar on wide screens.', 'squire' );
}
add_action( 'customize_register', 'squire_customize_register', 11 );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Squire 1.5
 * @see squire_customize_register()
 *
 * @return void
 */
function squire_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Squire 1.5
 * @see squire_customize_register()
 *
 * @return void
 */
function squire_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Register color schemes for Squire.
 *
 * Can be filtered with {@see 'squire_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Main Background Color.
 * 2. Sidebar Background Color.
 * 3. Box Background Color.
 * 4. Main Text and Link Color.
 * 5. Sidebar Text and Link Color.
 * 6. Meta Box Background Color.
 *
 * @since Squire 1.0
 *
 * @return array An associative array of color scheme options.
 */
function squire_get_color_schemes() {
	/**
	 * Filter the color schemes registered for use with Squire.
	 *
	 * The default schemes include 'default', 'luci', 'dark', 'pink', 'purple', and 'blue'.
	 *
	 * @since Squire 1.0
	 *
	 * @param array $schemes {
	 *     Associative array of color schemes data.
	 *
	 *     @type array $slug {
	 *         Associative array of information for setting up the color scheme.
	 *
	 *         @type string $label  Color scheme label.
	 *         @type array  $colors HEX codes for default colors prepended with a hash symbol ('#').
	 *                              Colors are defined in the following order: Main background, sidebar
	 *                              background, box background, main text and link, sidebar text and link,
	 *                              meta box background.
	 *     }
	 * }
	 */
	return apply_filters(
		'squire_color_schemes', array(
			'default' => array(
				'label'  => __( 'Default', 'squire' ),
				'colors' => array(
					'#f1f1f1',
					'#ffffff',
					'#ffffff',
					'#333333',
					'#333333',
					'#f7f7f7',
				),
			),
			'luci'  => array(
				'label'  => __( 'Luci', 'squire' ),
				'colors' => array(
					'#f9f9f9',
					'#662a72',
					'#ffffff',
					'#424142',
					'#da235b',
					'#f1f1f1',
				),
			),
			'dark'    => array(
				'label'  => __( 'Dark', 'squire' ),
				'colors' => array(
					'#111111',
					'#202020',
					'#202020',
					'#bebebe',
					'#bebebe',
					'#1b1b1b',
				),
			),
			'pink'    => array(
				'label'  => __( 'Pink', 'squire' ),
				'colors' => array(
					'#ffe5d1',
					'#e53b51',
					'#ffffff',
					'#352712',
					'#ffffff',
					'#f1f1f1',
				),
			),
			'purple'  => array(
				'label'  => __( 'Purple', 'squire' ),
				'colors' => array(
					'#674970',
					'#2e2256',
					'#ffffff',
					'#2e2256',
					'#ffffff',
					'#f1f1f1',
				),
			),
			'blue'   => array(
				'label'  => __( 'Blue', 'squire' ),
				'colors' => array(
					'#e9f2f9',
					'#55c3dc',
					'#ffffff',
					'#22313f',
					'#ffffff',
					'#f1f1f1',
				),
			),
		)
	);
}

if ( ! function_exists( 'squire_get_color_scheme' ) ) :
	/**
	 * Get the current Squire color scheme.
	 *
	 * @since Squire 1.0
	 *
	 * @return array An associative array of either the current or default color scheme hex values.
	 */
	function squire_get_color_scheme() {
		$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
		$color_schemes       = squire_get_color_schemes();

		if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
			return $color_schemes[ $color_scheme_option ]['colors'];
		}

		return $color_schemes['default']['colors'];
	}
endif; // squire_get_color_scheme

if ( ! function_exists( 'squire_get_color_scheme_choices' ) ) :
	/**
	 * Returns an array of color scheme choices registered for Squire.
	 *
	 * @return array Array of color schemes.
	 */
	function squire_get_color_scheme_choices() {
		$color_schemes                = squire_get_color_schemes();
		$color_scheme_control_options = array();

		foreach ( $color_schemes as $color_scheme => $value ) {
			$color_scheme_control_options[ $color_scheme ] = $value['label'];
		}

		return $color_scheme_control_options;
	}
endif; // squire_get_color_scheme_choices

if ( ! function_exists( 'squire_sanitize_color_scheme' ) ) :
	/**
	 * Sanitization callback for color schemes.
	 *
	 * @since Squire 1.0
	 *
	 * @param string $value Color scheme name value.
	 * @return string Color scheme name.
	 */
	function squire_sanitize_color_scheme( $value ) {
		$color_schemes = squire_get_color_scheme_choices();

		if ( ! array_key_exists( $value, $color_schemes ) ) {
			$value = 'default';
		}

		return $value;
	}
endif; // squire_sanitize_color_scheme

/**
 * Enqueues front-end CSS for color scheme.
 *
 * @see wp_add_inline_style()
 */
function squire_color_scheme_css() {
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );

	// Don't do anything if the default color scheme is selected.
	if ( 'default' === $color_scheme_option ) {
		return;
	}

	$color_scheme = squire_get_color_scheme();

	// Convert main and sidebar text hex color to rgba.
	$color_textcolor_rgb         = squire_hex2rgb( $color_scheme[3] );
	$color_sidebar_textcolor_rgb = squire_hex2rgb( $color_scheme[4] );
	$colors = array(
		'background_color'            => $color_scheme[0],
		'header_background_color'     => $color_scheme[1],
		'box_background_color'        => $color_scheme[2],
		'textcolor'                   => $color_scheme[3],
		'secondary_textcolor'         => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.7)', $color_textcolor_rgb ),
		'border_color'                => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.1)', $color_textcolor_rgb ),
		'border_focus_color'          => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.3)', $color_textcolor_rgb ),
		'sidebar_textcolor'           => $color_scheme[4],
		'sidebar_border_color'        => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.1)', $color_sidebar_textcolor_rgb ),
		'sidebar_border_focus_color'  => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.3)', $color_sidebar_textcolor_rgb ),
		'secondary_sidebar_textcolor' => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.7)', $color_sidebar_textcolor_rgb ),
		'meta_box_background_color'   => $color_scheme[5],
	);

	$color_scheme_css = squire_get_color_scheme_css( $colors );

	wp_add_inline_style( 'squire-style', $color_scheme_css );
}
add_action( 'wp_enqueue_scripts', 'squire_color_scheme_css' );

/**
 * Binds JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 */
function squire_customize_control_js() {
	wp_enqueue_script( 'color-scheme-control', get_template_directory_uri() . '/assets/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20141216', true );
	wp_localize_script( 'color-scheme-control', 'colorScheme', squire_get_color_schemes() );
}
add_action( 'customize_controls_enqueue_scripts', 'squire_customize_control_js' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 */
function squire_customize_preview_js() {
	wp_enqueue_script( 'squire-customize-preview', get_template_directory_uri() . '/assets/js/customize-preview.js', array( 'customize-preview' ), '20141216', true );
}
add_action( 'customize_preview_init', 'squire_customize_preview_js' );

/**
 * Returns CSS for the color schemes.
 *
 * @param array $colors Color scheme colors.
 * @return string Color scheme CSS.
 */
function squire_get_color_scheme_css( $colors ) {
	$colors = wp_parse_args(
		$colors, array(
			'background_color'            => '',
			'header_background_color'     => '',
			'box_background_color'        => '',
			'textcolor'                   => '',
			'secondary_textcolor'         => '',
			'border_color'                => '',
			'border_focus_color'          => '',
			'sidebar_textcolor'           => '',
			'sidebar_border_color'        => '',
			'sidebar_border_focus_color'  => '',
			'secondary_sidebar_textcolor' => '',
			'meta_box_background_color'   => '',
		)
	);

	$css = <<<CSS
	/* Color Scheme */

	/* Background Color */
	body {
		background-color: {$colors['background_color']};
	}

	/* Sidebar Background Color */
	.button,
	.button.primary,
	.menu .is-active > a,
	.menu .active > a,
	.label,
	.site-header,
	.wp-pagenavi > .current,
	.comment-form input[type="submit"] {
		background-color: {$colors['header_background_color']};
	}
	
	/* Sidebar Background Color */
	.comment-list .reply a,
	.comment-list .reply a:hover.disabled,
	.comment-list .reply a:hover[disabled],
	.comment-list .reply a:focus.disabled,
	.comment-list .reply a:focus[disabled] {
		border-color: {$colors['header_background_color']};
	}

	/* Box Background Color */
	.child-navigation {
		background-color: {$colors['box_background_color']};
	}

	/* Main Text Color */
	body,
	.child-navigation .menu > li > a {
		color: {$colors['textcolor']};
	}

	/* Secondary Text Color */
	a:hover,
	a:focus,
	.child-navigation .menu > li .is-active {
		color: {$colors['textcolor']};
	}

	/* Secondary Text Color */
	.child-navigation .menu > li > a {
		color: {$colors['secondary_textcolor']};
	}

	/* Sidebar Text Color */
	a,
	.child-navigation .menu > li .is-active {
		color: {$colors['secondary_sidebar_textcolor']};
	}

	/* Sidebar Border Focus Color */
	.main-navigation .menu > li > a:hover,
	.main-navigation .menu > li > a:active,
	.main-navigation .menu > li > a:focus,
	.main-navigation .menu > li.current_page_item > a,
	.main-navigation .menu > li.current-menu-parent > a,
	.button:hover,
	.button:focus {
		background-color: {$colors['sidebar_border_focus_color']};
	}

	/* Meta Background Color */
	.main-navigation .menu > li.current_page_item > a,
	.main-navigation .menu > li.current-menu-parent > a,
	.site-footer {
		color: {$colors['meta_box_background_color']};
	}
CSS;

	return $css;
}

/**
 * Output an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the Customizer
 * preview.
 */
function squire_color_scheme_css_template() {
	$colors = array(
		'background_color'            => '{{ data.background_color }}',
		'header_background_color'     => '{{ data.header_background_color }}',
		'box_background_color'        => '{{ data.box_background_color }}',
		'textcolor'                   => '{{ data.textcolor }}',
		'secondary_textcolor'         => '{{ data.secondary_textcolor }}',
		'border_color'                => '{{ data.border_color }}',
		'border_focus_color'          => '{{ data.border_focus_color }}',
		'sidebar_textcolor'           => '{{ data.sidebar_textcolor }}',
		'sidebar_border_color'        => '{{ data.sidebar_border_color }}',
		'sidebar_border_focus_color'  => '{{ data.sidebar_border_focus_color }}',
		'secondary_sidebar_textcolor' => '{{ data.secondary_sidebar_textcolor }}',
		'meta_box_background_color'   => '{{ data.meta_box_background_color }}',
	);
	?>
	<script type="text/html" id="tmpl-squire-color-scheme">
		<?php echo squire_get_color_scheme_css( $colors ); ?>
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'squire_color_scheme_css_template' );
