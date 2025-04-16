<?php
/**
 * Additional Function and Definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package    WordPress
 * @subpackage my-theme
 * @since      1.0
 */

/**
 *  Enqueue scripts and styles for the theme.
 *
 *  @since 1.0
 *  @return void
 */
function my_theme_styles_and_scripts() {
	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/style.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_styles_and_scripts' );
add_action( 'enqueue_block_assets', 'my_theme_styles_and_scripts' );

/**
 *  Render the block.
 *
 *  @since 1.0
 */
function my_theme_render_latest_posts_block() {
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 5,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		$output  = '<div class="latest-posts-block">';
		$output .= '<h2>Latest Posts</h2>';
		$output .= '<ul>';

		while ( $query->have_posts() ) {
			$query->the_post();
			$output .= '<li>';
			$output .= '<a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a>';
			$output .= '<p>' . get_the_date() . '</p>';
			$output .= '</li>';
		}

		$output .= '</ul>';
		$output .= '</div>';

		wp_reset_postdata();
		return $output;
	} else {
		return '<p>No posts found.</p>';
	}
}
