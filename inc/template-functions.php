<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Squire
 */

/**
 * Redirect child pages to parent + hash
 */
function squire_redirect_child_pages() {
	global $post;
	$post_slug = $post->post_name;
	if ( is_page() && $post->post_parent ) {
		wp_redirect( get_permalink( $post->post_parent ) . '#' . $post_slug );
	}
}

add_action( 'template_redirect', 'squire_redirect_child_pages' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function squire_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}

add_action( 'wp_head', 'squire_pingback_header' );

/**
 * Show kitchen sink by default
 *
 * @param $args
 *
 * @return mixed
 */
function squire_unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;

	return $args;
}

add_filter( 'tiny_mce_before_init', 'squire_unhide_kitchensink' );

/**
 * Renames sticky class.
 *
 * @param $classes
 *
 * @return array
 */
function squire_change_sticky_class( $classes ) {
	if ( in_array( 'sticky', $classes, true ) ) {
		$classes   = array_diff( $classes, array( 'sticky' ) );
		$classes[] = 'sticky-post';
	}

	return $classes;
}

add_filter( 'post_class', 'squire_change_sticky_class' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function squire_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	$page_id  = get_queried_object_id();
	$children = get_pages( 'child_of=' . $page_id );

	if ( count( $children ) != 0 && ! is_404() && ! is_home() && ! is_search() ) {
		$classes[] = 'has-child-pages';
	}

	return $classes;
}

add_filter( 'body_class', 'squire_body_classes' );

/**
 * Add Hex Block Shortcode
 *
 * @param $atts
 *
 * @return string
 */
function squire_hex_block_shortcode( $atts ) {
	$a = shortcode_atts(
		array(
			'name'    => '', // title of color
			'desc'    => '', // description of use
			'color'   => '', // hex code without #
			'columns' => '', // column settings
			'theme'   => '', // light/dark class
		), $atts
	);

	$color_block  = '';
	$style_output = '';
	$color_name   = $a['name'];
	$cell_class   = [ 'cell' ];

	if ( $a['columns'] ) {
		$cell_class[] = $a['columns'];
	} else {
		$cell_class[] = 'large-auto';
	}

	if ( $a['color'] ) {
		$style_output = 'style="background-color: #' . $a['color'] . ';"';
	}

	if ( $a['theme'] ) {
		$cell_class[] = $a['theme'];
	}

	$color_block .= '<div class="' . join( ' ', $cell_class ) . '">';
	$color_block .= '<button type="button" class="hex-block cell" ' . $style_output . '>';
	$color_block .= '<span class="color-name">' . esc_attr( $color_name ) . '</span>';
	$color_block .= '<span class="hex-code">#' . esc_attr( $a['color'] ) . '</span>';
	$color_block .= '<span class="copy-hex-code js-copy" data-clipboard-text="#' . esc_attr( $a['color'] ) . '" ' . $style_output . '>Copy hex code</span>';
	$color_block .= '</button>';
	$color_block .= '</div>';

	$error = '<p><span class="alert label">' . esc_html__( 'Invalid or missing hex code.', 'squire' ) . '</span></p>';

	if ( $a['color'] ) {
		return $color_block;
	} else {
		return $error;
	}

}

add_shortcode( 'hex', 'squire_hex_block_shortcode' );

// Add Quicktags
function squire_quicktags() {

	if ( wp_script_is( 'quicktags' ) ) {
		?>
		<script type="text/javascript">
			QTags.addButton('squire_lead', 'lead', '<p class="lead">', '</p>', 'Lead', '11',);
			QTags.addButton('squire_callout', 'callout', '<div class="callout">', '</div>', 'Callout', '11',);
		</script>
		<?php
	}

}

add_action( 'admin_print_footer_scripts', 'squire_quicktags' );

/**
 * Checks to see if we're on the homepage or not.
 */
function squire_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}
