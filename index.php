<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Squire
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main grid-x grid-margin-x small-up-1 medium-up-2 large-up-3">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
			?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cell' ); ?>>
					<header class="entry-header">
						<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
						<div class="entry-meta">
							<?php squire_date(); ?>
						</div>
					</header><!-- .entry-header -->

					<?php squire_post_thumbnail(); ?>

					<div class="entry-content">
						<?php the_excerpt(); ?>
					</div><!-- .entry-content -->

					<footer class="entry-footer">
						<?php squire_entry_profile(); ?>

						<div class="entry-meta">
							<?php echo get_the_category_list(); ?>
						</div><!-- .entry-meta -->
					</footer><!-- .entry-footer -->
				</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->

		<?php
		if ( function_exists( 'wp_pagenavi' ) ) :
			wp_pagenavi();
		else :
			the_posts_navigation();
		endif;
		?>
	</div><!-- #primary -->

<?php
get_footer();
