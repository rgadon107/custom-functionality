<?php

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

use function gardenClubOfMpls\CustomFunctionalityPlugin\_get_plugin_directory;

add_shortcode( 'member_photo_directory', __NAMESPACE__ . '\member_photo_directory_shortcode_handler' );

/**
 * Shortcode: [member_photo_directory]
 *
 * Shortcode to display the Garden Club of Minneapolis member photo directory.
 *
 * @param array|string $user_defined_attributes Shortcode attributes passed by WordPress.
 *
 * @return string The HTML template view for the PDF viewer.
 */
function member_photo_directory_shortcode_handler( array|string $user_defined_attributes ): string {
	// Enqueue scripts and styles ONLY on the pages displaying this block
	wp_enqueue_style( 'member-photo-directory-viewer-styles' );
	wp_enqueue_script( 'member-photo-directory-loader' );

	// 1. Fetch default configuration array from updated configuration/shortcodes path
	$config_path = _get_plugin_directory() . '/src/shortcodes/config/member-photo-directory.php';

	$defaults = file_exists( $config_path ) ? require $config_path : array(
		'desktop_url' => '',
		'tablet_url'  => '',
		'mobile_url'  => '',
	);

	// 2. Merge shortcode attributes with the configuration defaults
	$atts = shortcode_atts(
		$defaults,
		$user_defined_attributes,
		'member_photo_directory'
	);

	$desktop_pdf_url = $atts['desktop_url'];
	$tablet_pdf_url  = $atts['tablet_url'];
	$mobile_pdf_url  = $atts['mobile_url'];

	// 3. Render HTML view template
	ob_start();

	$template_path = _get_plugin_directory() . '/src/templates/member-photo-directory-view.php';
	if ( file_exists( $template_path ) ) {
		include $template_path;
	}

	$output = ob_get_clean();

	return is_string( $output ) ? $output : '';
}
