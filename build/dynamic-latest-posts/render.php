<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 * @package my-theme
 */

$get_per_page = 3;
if ( ! empty( $attributes['Maximum'] ) ) {
	$get_per_page = $attributes['Maximum'];
}

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $get_per_page,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) {

	$output = '<div>';

	while ( $query->have_posts() ) {
		$query->the_post();
		$output .= '<div>';
		$output .= '<a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a>';
		if ( $attributes['showDate'] ) {
			$output .= '<div>' . get_the_date() . '</div>';
		}
		if ( $attributes['showDescription'] ) {
			$output .= '<div>' . get_the_excerpt() . '</div>';
		}
		if ( $attributes['showFeaturedImage'] ) {
			$output .= '<div>' . get_the_post_thumbnail() . '</div>';
		}
		$output .= '</div>';
	}

	$output .= '</div>';

} else {
	$output = '<p>No posts found.</p>';
}
wp_reset_postdata();

$block_content = '<div ' . get_block_wrapper_attributes() . '>' . wp_kses_post( $output ) . '</div>';
echo wp_kses_post( $block_content );
