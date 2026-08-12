<?php

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

use function gardenClubOfMpls\CustomFunctionalityPlugin\_get_plugin_directory;

add_shortcode( 'member_photo_directory', __NAMESPACE__ . '\member_photo_directory_shortcode_handler' );

/**
 * Shortcode: [member_photo_directory]
 *
 * Shortcode to display the Garden Club of Minneapolis member photo directory.
 * Returns an embedded PDF file view that responds to the user's screen width.
 * * @param array|string $user_defined_attributes Shortcode attributes passed by WordPress.
 * @type string $desktop_url The URL of the PDF file for desktop screens.
 * @type string $tablet_url  The URL of the PDF file for tablet screens.
 * @type string $mobile_url  The URL of the PDF file for mobile screens.
 *
 * @return string The HTML template view to render the member photo directory file.
 */
function member_photo_directory_shortcode_handler( array|string $user_defined_attributes ): string {
	// Enqueue scripts and styles ONLY on the pages displaying this block
	wp_enqueue_style( 'member-photo-directory-viewer-styles' );
	wp_enqueue_script( 'member-photo-directory-loader' );

	// Merge user attributes with default values in a single call
	$atts = shortcode_atts(
		array(
			'desktop_url' => '',
			'tablet_url'  => '',
			'mobile_url'  => '',
		),
		$user_defined_attributes,
		'member_photo_directory'
	);

	$desktop_pdf_url = $atts['desktop_url'];
	$tablet_pdf_url  = $atts['tablet_url'];
	$mobile_pdf_url  = $atts['mobile_url'];

	ob_start();

	$template_path = _get_plugin_directory() . '/src/templates/member-photo-directory-view.php';
	if ( file_exists( $template_path ) ) {
		include $template_path;
	}

	$output = ob_get_clean();

	return is_string( $output ) ? $output : '';
}
