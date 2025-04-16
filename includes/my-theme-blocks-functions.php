<?php
/**
 * Block Function and Definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package    WordPress
 * @subpackage my-theme
 * @since      1.0
 */

/**
 *  Enqueue scripts and styles for blocks.
 *
 *  @since 1.0
 *  @return void
 */
function my_theme_native_blocks_scripts() {
}
add_action( 'enqueue_block_assets', 'my_theme_native_blocks_scripts' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function my_theme_block_init() {
	$build_dir = get_template_directory() . '/build';
	// phpcs:ignore
	// $build_dir = __DIR__ . '/build';

	if ( is_dir( $build_dir ) ) {
		$blocks = scandir( $build_dir );

		if ( false !== $blocks ) {
			foreach ( $blocks as $block ) {
				$block_location = $build_dir . '/' . $block;

				if ( is_dir( $block_location ) && ! in_array( $block, array( '.', '..' ), true ) ) {
					register_block_type( $block_location );
				}
			}
		}
	}
}
add_action( 'init', 'my_theme_block_init' );

/**
 * Register Rest Image src.
 */
function my_theme_add_replace_fields() {
	register_rest_field(
		array( 'post', 'page' ),
		'featured_src',
		array(
			'get_callback'    => 'my_theme_get_image_src',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'my_theme_add_replace_fields' );

/**
 * Callback function image src.
 *
 * @param object $object_s  The HTML attributes applied to the feature image.
 */
function my_theme_get_image_src( $object_s ) {
	$feat_img_array = wp_get_attachment_image_url(
		$object_s['featured_media'],
		'full',
		true
	);
	return ! empty( $feat_img_array ) ? esc_url( $feat_img_array ) : '';
}
/**
 * Parse blocks.
 *
 * @param array $block_name Block name.
 * @param array $attributes Attribute list.
 */
function my_theme_generate_css_from_attributes( $block_name, $attributes ) {

	$xl = array();
	$lg = array();
	$sm = array();

	$css = array();
	foreach ( $attributes as $key => $value ) {
		// Add more conditions for specific attribute handling as needed.
		switch ( $key ) {
			case 'blockId':
				$section = '.section-' . $value;
				$heading = '.heading-' . $value;
				$paragra = '.paragraph-' . $value;
				break;
			// Heading.
			case 'xl_fontsize':
				$xl[] = "font-size: $value !important;";
				break;
			case 'xl_textalign':
				$xl[] = "text-align: $value !important;";
				break;
			case 'xl_color':
				$xl[] = "color: $value !important;";
				break;
			case 'fontweight':
				$xl[] = ( isset( $value ) && ! empty( $value ) ) ? 'font-weight:' . esc_attr( $value ) . ' !important;' : '';
				break;
			case 'lg_fontsize':
				$lg[] = "font-size: $value !important;";
				break;
			case 'lg_color':
				$lg[] = "color: $value !important;";
				break;
			case 'lg_textalign':
				$lg[] = "text-align: $value !important;";
				break;
			case 'tb_fontsize':
				$tb[] = "font-size: $value !important;";
				break;
			case 'tb_color':
				$tb[] = "color: $value !important;";
				break;
			case 'tb_textalign':
				$tb[] = "text-align: $value !important;";
				break;
			case 'sm_fontsize':
				$sm[] = "font-size: $value !important;";
				break;
			case 'sm_color':
				$sm[] = "color: $value !important;";
				break;
			case 'sm_textalign':
				$sm[] = "text-align: $value !important;";
				break;

			// paragraph.
			case 'xl_p_fontsize':
				$xlp[] = "font-size: $value !important;";
				break;
			case 'xl_p_textalign':
				$xlp[] = "text-align: $value !important;";
				break;
			case 'xl_p_color':
				$xlp[] = "color: $value !important;";
				break;
			case 'lg_p_fontsize':
				$lgp[] = "font-size: $value !important;";
				break;
			case 'lg_p_color':
				$lgp[] = "color: $value !important; ";
				break;
			case 'lg_p_textalign':
				$lgp[] = "text-align: $value !important;";
				break;
			case 'tb_p_fontsize':
				$tbp[] = "font-size: $value !important;";
				break;
			case 'tb_p_color':
				$tbp[] = "color: $value !important;";
				break;
			case 'tb_p_textalign':
				$tbp[] = "text-align: $value !important;";
				break;
			case 'sm_p_fontsize':
				$smp[] = "font-size: $value !important;";
				break;
			case 'sm_p_color':
				$smp[] = "color: $value !important;";
				break;
			case 'sm_p_textalign':
				$smp[] = "text-align: $value !important;";
				break;
			// Padding & Margin.
			case 'xl_padding':
				if ( ! empty( $value ) ) :
					$pxp[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'padding-top:' . esc_attr( $value['top'] ) . ';' : '';
					$pxp[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'padding-right:' . esc_attr( $value['right'] ) . ';' : '';
					$pxp[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'padding-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$pxp[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'padding-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			case 'xl_margin':
				if ( ! empty( $value ) ) :
					$mxl[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'margin-top:' . esc_attr( $value['top'] ) . ';' : '';
					$mxl[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'margin-right:' . esc_attr( $value['right'] ) . ';' : '';
					$mxl[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'margin-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$mxl[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'margin-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			case 'lg_padding':
				if ( ! empty( $value ) ) :
					$plp[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'padding-top:' . esc_attr( $value['top'] ) . ';' : '';
					$plp[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'padding-right:' . esc_attr( $value['right'] ) . ';' : '';
					$plp[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'padding-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$plp[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'padding-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			case 'lg_margin':
				if ( ! empty( $value ) ) :
					$mlg[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'margin-top:' . esc_attr( $value['top'] ) . ';' : '';
					$mlg[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'margin-right:' . esc_attr( $value['right'] ) . ';' : '';
					$mlg[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'margin-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$mlg[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'margin-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			case 'tb_padding':
				if ( ! empty( $value ) ) :
					$ptb[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'padding-top:' . esc_attr( $value['top'] ) . ';' : '';
					$ptb[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'padding-right:' . esc_attr( $value['right'] ) . ';' : '';
					$ptb[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'padding-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$ptb[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'padding-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			case 'tb_margin':
				if ( ! empty( $value ) ) :
					$mtb[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'margin-top:' . esc_attr( $value['top'] ) . ';' : '';
					$mtb[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'margin-right:' . esc_attr( $value['right'] ) . ';' : '';
					$mtb[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'margin-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$mtb[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'margin-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			case 'sm_padding':
				if ( ! empty( $value ) ) :
					$psm[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'padding-top:' . esc_attr( $value['top'] ) . ';' : '';
					$psm[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'padding-right:' . esc_attr( $value['right'] ) . ';' : '';
					$psm[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'padding-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$psm[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'padding-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			case 'sm_margin':
				if ( ! empty( $value ) ) :
					$msm[] = ( isset( $value['top'] ) && ! empty( $value['top'] ) ) ? 'margin-top:' . esc_attr( $value['top'] ) . ';' : '';
					$msm[] = ( isset( $value['right'] ) && ! empty( $value['right'] ) ) ? 'margin-right:' . esc_attr( $value['right'] ) . ';' : '';
					$msm[] = ( isset( $value['bottom'] ) && ! empty( $value['bottom'] ) ) ? 'margin-bottom:' . esc_attr( $value['bottom'] ) . ';' : '';
					$msm[] = ( isset( $value['left'] ) && ! empty( $value['left'] ) ) ? 'margin-top:' . esc_attr( $value['left'] ) . ';' : '';
				endif;
				break;
			// No need for a default case if you don't want to reset the arrays.
		}
	}

	$general = '';
	$large   = '';
	$tablet  = '';
	$small   = '';

	$pxp = ! empty( $pxp ) ? $pxp : array();
	$mxl = ! empty( $mxl ) ? $mxl : array();
	$plp = ! empty( $plp ) ? $plp : array();
	$mlg = ! empty( $mlg ) ? $mlg : array();
	$ptb = ! empty( $ptb ) ? $ptb : array();
	$mtb = ! empty( $mtb ) ? $mtb : array();
	$psm = ! empty( $psm ) ? $psm : array();
	$msm = ! empty( $msm ) ? $msm : array();

	// Desktop Size CSS.
	$general .= ( ! empty( $xl ) && ! empty( $heading ) ) ? esc_html( $heading ) . '{ ' . wp_kses_post( implode( "\n", $xl ) ) . ' }' : '';
	$general .= ( ! empty( $xlp ) && ! empty( $paragra ) ) ? esc_html( $paragra ) . '{ ' . wp_kses_post( implode( "\n", $xlp ) ) . ' }' : '';
	$general .= ( ( ! empty( $pxp ) || ! empty( $mxl ) ) && ! empty( $section ) ) ? esc_html( $section ) . '{ ' . wp_kses_post( implode( ' ', $pxp ) ) . '' . wp_kses_post( implode( ' ', $mxl ) ) . '}' : '';

	// Large Size CSS.
	$large .= ( ! empty( $lg ) ) ? esc_html( $heading ) . '{ ' . wp_kses_post( implode( "\n", $lg ) ) . ' }' : '';
	$large .= ( ! empty( $lgp ) ) ? esc_html( $paragra ) . '{ ' . wp_kses_post( implode( "\n", $lgp ) ) . ' }' : '';
	$large .= ( ( ! empty( $plp ) || ! empty( $mlg ) ) && ! empty( $section ) ) ? esc_html( $section ) . '{ ' . wp_kses_post( implode( ' ', $plp ) ) . '' . wp_kses_post( implode( ' ', $mlg ) ) . '}' : '';

	// Tablet Size CSS.
	$tablet .= ( ! empty( $tb ) ) ? esc_html( $heading ) . '{ ' . wp_kses_post( implode( "\n", $tb ) ) . ' }' : '';
	$tablet .= ( ! empty( $tbp ) ) ? esc_html( $paragra ) . '{ ' . wp_kses_post( implode( "\n", $tbp ) ) . ' }' : '';
	$tablet .= ( ( ! empty( $ptb ) || ! empty( $mtb ) ) && ! empty( $section ) ) ? esc_html( $section ) . '{ ' . wp_kses_post( implode( ' ', $ptb ) ) . '' . wp_kses_post( implode( ' ', $mtb ) ) . '}' : '';

	// Mobile Size CSS.
	$small .= ( ! empty( $sm ) ) ? esc_html( $heading ) . '{ ' . wp_kses_post( implode( "\n", $sm ) ) . ' }' : '';
	$small .= ( ! empty( $smp ) ) ? esc_html( $paragra ) . '{ ' . wp_kses_post( implode( "\n", $smp ) ) . ' }' : '';
	$small .= ( ( ! empty( $psm ) || ! empty( $msm ) ) && ! empty( $section ) ) ? esc_html( $section ) . '{ ' . wp_kses_post( implode( ' ', $psm ) ) . '' . wp_kses_post( implode( ' ', $msm ) ) . '}' : '';

	return array(
		'general' => $general,
		'large'   => $large,
		'tablet'  => $tablet,
		'small'   => $small,
	);
}

/**
 * Parse blocks.
 */
function my_theme_generated_block_styles() {
	// Get all block types.
	$blocks        = my_theme_blocks_parse_blocks();
	$generated_css = array();
	$general       = '';
	$large         = '';
	$tablet        = '';
	$small         = '';

	foreach ( $blocks as $key => $block_data ) :
		if ( $block_data ) :
			$generated_css = my_theme_generate_css_from_attributes( $block_data['blockName'], $block_data['attrs'] );
			$general      .= $generated_css['general'];
			$large        .= $generated_css['large'];
			$tablet       .= $generated_css['tablet'];
			$small        .= $generated_css['small'];
		endif;
	endforeach;

	ob_start();
	echo ! empty( $general ) ? wp_kses_post( $general ) : '';
	echo ( ! empty( $large ) ) ? '@media only screen and (max-width:1200px) {' . wp_kses_post( $large ) . '}' : '';
	echo ( ! empty( $tablet ) ) ? '@media only screen and (max-width:1024px) {' . wp_kses_post( $tablet ) . '}' : '';
	echo ( ! empty( $small ) ) ? '@media only screen and (max-width:767px) {' . wp_kses_post( $small ) . '}' : '';
	$css = ob_get_clean();

	// Add the dynamically generated CSS.
	wp_add_inline_style( 'theme-style', $css );
}
add_action( 'wp_enqueue_scripts', 'my_theme_generated_block_styles' );

/**
 * Parse blocks.
 *
 * @return array block array.
 */
function my_theme_blocks_parse_blocks() {

	global $post;
	$ks_blocks = array(
		'my-theme/dynamic-copyright-year',
	);

	$block_data = array();
	if ( $post ) {
		$blocks = isset( $post->post_content ) ? parse_blocks( $post->post_content ) : array();
		foreach ( $blocks as $block ) {
			// Check block in array.
			if ( in_array( $block['blockName'], $ks_blocks, true ) ) {
				$block_data[] = $block;
			}
		}
	}
	return $block_data;
}
