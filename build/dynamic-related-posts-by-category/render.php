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

// Default values.
$posts_to_show        = 3;
$image_size           = 'medium';
$excerpt_length       = 55;
$show_featured_image  = true;
$show_excerpt         = true;
$show_date            = true;
$show_categories      = true;
$show_author          = false;
$exclude_current_post = true;
$order_by             = 'date';
$order_post           = 'DESC';
$layout               = 'list';
$columns              = 2;
$enable_pagination    = false;
$category_filtering   = 'current';
$specific_categories  = array();

// Get attribute values.
if ( ! empty( $attributes['postsToShow'] ) ) {
	$posts_to_show = $attributes['postsToShow'];
}
if ( isset( $attributes['showFeaturedImage'] ) ) {
	$show_featured_image = $attributes['showFeaturedImage'];
}
if ( isset( $attributes['showExcerpt'] ) ) {
	$show_excerpt = $attributes['showExcerpt'];
}
if ( isset( $attributes['showDate'] ) ) {
	$show_date = $attributes['showDate'];
}
if ( isset( $attributes['showCategories'] ) ) {
	$show_categories = $attributes['showCategories'];
}
if ( isset( $attributes['showAuthor'] ) ) {
	$show_author = $attributes['showAuthor'];
}
if ( isset( $attributes['excludeCurrentPost'] ) ) {
	$exclude_current_post = $attributes['excludeCurrentPost'];
}
if ( ! empty( $attributes['orderBy'] ) ) {
	$order_by = $attributes['orderBy'];
}
if ( ! empty( $attributes['order'] ) ) {
	$order_post = $attributes['order'];
}
if ( ! empty( $attributes['layout'] ) ) {
	$layout = $attributes['layout'];
}
if ( ! empty( $attributes['columns'] ) ) {
	$columns = $attributes['columns'];
}
if ( isset( $attributes['enablePagination'] ) ) {
	$enable_pagination = $attributes['enablePagination'];
}
if ( ! empty( $attributes['categoryFiltering'] ) ) {
	$category_filtering = $attributes['categoryFiltering'];
}
if ( ! empty( $attributes['specificCategories'] ) ) {
	$specific_categories = $attributes['specificCategories'];
}
if ( ! empty( $attributes['imageSize'] ) ) {
	$image_size = $attributes['imageSize'];
}
if ( ! empty( $attributes['excerptLength'] ) ) {
	$excerpt_length = $attributes['excerptLength'];
}

// Set up query arguments.
$args = array(
	'post_type'           => 'post',
	'posts_per_page'      => $posts_to_show,
	'post_status'         => 'publish',
	'orderby'             => $order_by,
	'order'               => $order,
	'ignore_sticky_posts' => true,
);

// Handle pagination.
if ( $enable_pagination ) {
	$get_paged         = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args['paged'] = $get_paged;
}

// Exclude current post if needed.
if ( $exclude_current_post ) {
	$args['post__not_in'] = array( get_the_ID() );
}

// Handle category filtering.
if ( 'current' === $category_filtering ) {
	// Get categories of the current post.
	$current_post_categories = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );
	if ( ! empty( $current_post_categories ) ) {
		$args['category__in'] = $current_post_categories;
	}
} elseif ( 'specific' === $category_filtering && ! empty( $specific_categories ) ) {
	$args['category__in'] = $specific_categories;
}

// Run the query.
$related_posts_query = new WP_Query( $args );

// Start building the output.
$classes = array(
	'related-posts-container',
	'related-posts-layout-' . $layout,
	'columns-' . $columns,
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => implode( ' ', $classes ),
	)
);

$output = '<div ' . $wrapper_attributes . '>';

if ( $related_posts_query->have_posts() ) {
	$output .= '<div class="related-posts-grid">';

	while ( $related_posts_query->have_posts() ) {
		$related_posts_query->the_post();

		$get_post_id   = get_the_ID();
		$permalink = get_permalink();
		$get_title     = get_the_title();

		$output .= '<article id="post-' . $get_post_id . '" class="related-post">';

		// Featured image.
		if ( $show_featured_image && has_post_thumbnail() ) {
			$output .= '<div class="related-post-image">';
			$output .= '<a href="' . esc_url( $permalink ) . '">';
			$output .= get_the_post_thumbnail( $get_post_id, $image_size );
			$output .= '</a>';
			$output .= '</div>';
		}

		$output .= '<div class="related-post-content">';

		// Title.
		$output .= '<h3 class="related-post-title">';
		$output .= '<a href="' . esc_url( $permalink ) . '">' . esc_html( $get_title ) . '</a>';
		$output .= '</h3>';

		// Categories.
		if ( $show_categories ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$output   .= '<div class="related-post-categories">';
				$cat_links = array();
				foreach ( $categories as $category ) {
					$cat_links[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
				}
				$output .= implode( ', ', $cat_links );
				$output .= '</div>';
			}
		}

		// Date.
		if ( $show_date ) {
			$output .= '<div class="related-post-date">';
			$output .= get_the_date();
			$output .= '</div>';
		}

		// Author.
		if ( $show_author ) {
			$output .= '<div class="related-post-author">';
			$output .= esc_html__( 'By', 'my-theme' ) . ' ';
			$output .= '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
			$output .= '</div>';
		}

		// Excerpt.
		if ( $show_excerpt ) {
			$excerpt         = get_the_excerpt();
			$trimmed_excerpt = wp_trim_words( $excerpt, $excerpt_length, '...' );

			$output .= '<div class="related-post-excerpt">';
			$output .= wp_kses_post( $trimmed_excerpt );
			$output .= '</div>';
		}

		$output .= '</div>'; // End of related-post-content.
		$output .= '</article>';
	}

	$output .= '</div>'; // End of related-posts-grid.

	// Pagination.
	if ( $enable_pagination ) {
		$output .= '<div class="related-posts-pagination">';
		$output .= paginate_links(
			array(
				'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $related_posts_query->max_num_pages,
				'prev_text' => '&laquo; ' . esc_html__( 'Previous', 'my-theme' ),
				'next_text' => esc_html__( 'Next', 'my-theme' ) . ' &raquo;',
				'type'      => 'list',
				'end_size'  => 3,
				'mid_size'  => 3,
			)
		);
		$output .= '</div>';
	}
} else {
	$output .= '<p class="no-related-posts">' . esc_html__( 'No related posts found.', 'my-theme' ) . '</p>';
}

$output .= '</div>'; // End of related-posts-container.

wp_reset_postdata();

echo wp_kses_post( $output );
