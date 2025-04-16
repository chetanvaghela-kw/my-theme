<?php
/**
 * Functions.php
 *
 * @package my-theme
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function theme_setup() {

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'header-menu' => esc_html__( 'Header Menu', 'bhm' ),
		)
	);

	// Add support for block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for woocommerce.
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'theme_setup' );

require_once get_template_directory() . '/includes/additional-functions.php';
require_once get_template_directory() . '/includes/my-theme-blocks-functions.php';
require_once get_template_directory() . '/includes/cpt-custom-fields.php';
