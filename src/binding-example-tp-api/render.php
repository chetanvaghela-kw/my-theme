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

// Fetch API data.
$response = wp_remote_get( 'https://jsonplaceholder.typicode.com/posts/1' );

if ( is_wp_error( $response ) ) {
	return '<p>Error fetching data.</p>';
}

$body = wp_remote_retrieve_body( $response );
$data = json_decode( $body, true );

// Render the block output.
$output = sprintf(
	'<div class="my-theme-api-block">
		 <h3>%s</h3>
		 <p>%s</p>
	 </div>',
	esc_html( $data['title'] ),
	esc_html( $data['body'] )
);

$block_content = '<div ' . get_block_wrapper_attributes() . '><h3>API Data:</h3>' . wp_kses_post( $output ) . '</div>';
echo wp_kses_post( $block_content );
