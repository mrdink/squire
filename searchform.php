<?php
/**
 * The template for displaying the search form.
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package Squire
 */

?>

<form id="site-search" role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<input class="search-input" type="search" placeholder="<?php esc_html_e( 'Search', 'squire' ); ?>" value="<?php echo esc_html( get_search_query() ); ?>" name="s" title="<?php _e( 'Search for:', 'squire' ); ?>" tabindex="1"/>
	<input type="submit" class="search-submit" value="<?php esc_html_e( 'Search', 'squire' ); ?>"/>
</form>
