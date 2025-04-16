<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package my-theme
 */

get_header(); ?>

<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'my-theme' ); ?></h1>

<div class="page-content">
	<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'my-theme' ); ?></p>			
	
</div><!-- .page-content -->
			
<?php get_footer(); ?>
