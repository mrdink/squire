<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Squire
 */
$description = get_bloginfo( 'description', 'display' );
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'squire' ); ?></a>

	<div class="search-panel off-canvas position-left" id="js-search" data-off-canvas data-auto-focus="true">
		<?php echo get_search_form(); ?>

		<a class="secondary button" aria-label="Close menu" data-close>
			<i class="fa fa-angle-left" aria-hidden="true"></i>
		</a>
	</div>


	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;

if ( $description || is_customize_preview() ) :
			?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
				<?php
			endif;
			?>

			<button class="button menu-toggle hide-for-large" data-toggle="site-navigation secondary" aria-controls="site-navigation" aria-expanded="false">
				<i class="fa fa-bars"></i>
				<span class="screen-reader-text"><?php esc_html_e( 'Main navigation toggle', 'squire' ); ?></span>
			</button>

			<button class="button search-toggle" data-toggle="js-search" aria-controls="js-search" aria-expanded="false">
				<i class="fa fa-search"></i>
				<span class="screen-reader-text"><?php esc_html_e( 'Off Canvas Search Form', 'squire' ); ?></span>
			</button>
		</div><!-- .site-branding -->

		<?php if ( has_nav_menu( 'primary-menu' ) ) : ?>
			<nav id="site-navigation" class="main-navigation animated" data-toggler=".is-visible fadeIn" aria-label="<?php esc_html_e( 'Primary Menu', 'squire' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'container'      => false,
						'theme_location' => 'primary-menu',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'vertical menu accordion-menu',
						'items_wrap'     => '<ul id="%1$s" class="%2$s" data-accordion-menu data-submenu-toggle>%3$s</ul>',
						'walker'         => new Menu_Dropdown_Walker(),
					)
				);
				?>
			</nav><!-- #site-navigation -->

			<?php if ( has_nav_menu( 'social-links-menu' ) ) : ?>
				<nav class="social-navigation show-for-medium" aria-label="<?php esc_html_e( 'Social Links Menu', 'squire' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'container'      => false,
							'theme_location' => 'social-links-menu',
							'menu_class'     => 'social-links-menu menu',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						)
					);
					?>
				</nav><!-- .social-navigation -->
			<?php endif; ?>
		<?php endif; ?>

		<?php get_sidebar(); ?>

		<footer id="colophon" class="site-footer show-for-large">
			<div class="site-info">
				<?php
				echo sprintf(
					// translators: %s Date for copyright
					esc_html__( '&copy; %s. All rights reserved.', 'squire' ),
					esc_attr( current_time( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</header><!-- #masthead -->

	<?php squire_child_page_nav(); ?>

	<?php

	/*
	 * If a regular post or page, and not the front page, show the featured image.
	 * Using get_queried_object_id() here since the $post global may not be set before a call to the_post().
	 */
	if ( ( is_single() || ( is_page() && ! squire_is_frontpage() ) ) && has_post_thumbnail( get_queried_object_id() ) ) :
		echo '<div class="single-featured-image-header">';
		echo get_the_post_thumbnail( get_queried_object_id(), 'squire-featured-image' );
		echo '</div><!-- .single-featured-image-header -->';
	endif;
	?>

	<div id="content" class="site-content">
