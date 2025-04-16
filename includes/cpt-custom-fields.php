<?php
/**
 * CPT and Custom Fields
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package    WordPress
 * @subpackage my-theme
 * @since      1.0
 */

/**
 *  Register custom fields for pages in WordPress using register_meta()
 *
 *  @since 1.0
 *  @return void
 */
function my_theme_register_custom_meta_fields_for_pages() {
	register_meta(
		'post',
		'my_theme_famous_quote',
		array(
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'wp_strip_all_tags',
			'show_in_rest'      => true,
		)
	);

	// Register the image field "my_theme_photo" for pages.
	register_meta(
		'post',
		'my_theme_photo',
		array(
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'my_theme_register_custom_meta_fields_for_pages' );
