<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Squire
 */

if ( ! function_exists( 'squire_entry_profile' ) ) :
	/**
	 * Entry profile for posts
	 */
	function squire_entry_profile() {
		?>
		<div class="entry-profile-pic">
			<?php if ( get_avatar( get_the_author_meta( 'user_email' ) ) ) { ?>
				<a href="<?php get_the_author_meta( 'url' ); ?>"><?php echo get_avatar( get_the_author_meta( 'user_email' ), '60' ); ?></a>
			<?php } ?>
			<span class="byline author vcard"><?php echo __( 'By', 'squire' ); ?> <a
					href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"
					class="fn"><?php echo get_the_author(); ?></a>
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
			$pages[]   = $post;
			$post_slug = $post->post_name;
			?>

			<article id="<?php echo $post_slug; ?>" <?php post_class(); ?>
					 data-magellan-target="<?php echo $post_slug; ?>" aria-describedby="<?php echo get_the_title(); ?>">
				<header class="entry-header child-entry-header">
					<?php the_title( '<h2 class="entry-title"><a href="#' . $post_slug . '">', '</a></h2>' ); ?>
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

if ( ! function_exists( 'squire_date' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 */
	function squire_date() {
		$post_date = the_date( 'Y-m-d', '', '', false );
		$month_ago = date( 'Y-m-d', mktime( 0, 0, 0, date( 'm' ) - 1, date( 'd' ), date( 'Y' ) ) );
		if ( $post_date > $month_ago ) {
			/* translators: %s: post date. */
			$post_date = sprintf( __( '%1$s ago', 'squire' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
		} else {
			$post_date = get_the_date();
		}
		printf(
			/* translators: %s: post date. */
			__( '<time class="entry-date" datetime="%1$s" pubdate>Posted %2$s</time>', 'squire' ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( $post_date )
		);
	}

endif;

if ( ! function_exists( 'squire_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * Create your own squire_post_thumbnail() function to override in a child theme.
	 */
	function squire_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() || is_single() ) {
			return;
		}
		?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
			<?php
			the_post_thumbnail(
				'post-thumbnail', array(
					'alt' => the_title_attribute( 'echo=0' ),
				)
			);
			?>
		</a>

		<?php
	}
endif;
