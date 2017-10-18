<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Squire
 */

if ( ! function_exists( 'squire_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function squire_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'squire' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'squire' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'squire_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function squire_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'squire' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'squire' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'squire' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'squire' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'squire' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'squire' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'squire_entry_profile' ) ) :
	/**
	 * Entry profile for posts
	 */
	function squire_entry_profile() {
		?>
		<div class="entry-profile">
			<?php if ( get_avatar( get_the_author_meta( 'user_email' ) ) ) { ?>
				<a href="<?php get_the_author_meta( 'url' ); ?>"><?php echo get_avatar( get_the_author_meta( 'user_email' ), '60' ); ?></a>
			<?php } ?>
			<span class="byline author vcard"><?php echo __( 'By', 'squire' ); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a>
		</div>
		<?php
	}
endif;

/**
 * Grab child pages
 */
function squire_get_child_pages() {

	global $post;
	$parent_id = $post->ID;
	$pages     = array();

	// WP_Query arguments
	$args = array(
		'post_type'      => 'page',
		'post_parent'    => $parent_id,
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'no_found_rows'  => true,
		'nopaging'       => true,
		'posts_per_page' => - 1,
	);

	// The Query
	$wp_query = new WP_Query( $args );

	// The Loop
	if ( $wp_query->have_posts() ) {

		while ( $wp_query->have_posts() ) {

			$wp_query->the_post();
			$pages[] = $post;
			$post_slug = $post->post_name; ?>

			<article id="<?php echo $post_slug ?>" <?php post_class(); ?> data-magellan-target="<?php echo $post_slug ?>" aria-describedby="<?php echo get_the_title(); ?>">
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php

					if ( ! empty( get_the_content() ) ) :
						the_content();
					endif;

					edit_post_link(
						sprintf(
							wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Edit <span class="screen-reader-text">%s</span>', 'squire' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							get_the_title()
						),
						'<span class="edit-link">',
						'</span>'
					);

					?>
				</div><!-- .entry-content -->
			</article><!-- #post-## -->

			<?php
		}
	}

	// Restore original Post Data
	wp_reset_postdata();
}

/**
 * Generate child page navigation
 */
function squire_child_page_nav() {

	$page_id  = get_queried_object_id();
	$children = get_pages( 'child_of=' . $page_id );

	if ( count( $children ) != 0 && ! is_404() && ! is_home() && ! is_search() ) :
		?>
		<nav id="sub-navigation" class="child-navigation">
			<ul class="vertical menu" data-magellan data-offset="24">
				<li class="menu-text">Page Navigation</li>
				<?php
				global $post;
				wp_reset_query();

				$args = array(
					'post_parent'    => $page_id,
					'post_type'      => 'page',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'nopaging'       => true,
					'posts_per_page' => - 1,
				);

				$sub_pages = new WP_Query( $args );

				if ( $sub_pages->have_posts() ) :
					while ( $sub_pages->have_posts() ) :
						$sub_pages->the_post();
						$post_slug = $post->post_name;
						echo '<li><a href="#' . $post_slug . '">' . get_the_title() . '</a></li>';
					endwhile;
				endif;

				wp_reset_query();
				?>
				<li class="back-to-top show-for-medium"><a href="#main">Back to top</a></li>
			</ul>
		</nav>

		<?php
	endif;
}
