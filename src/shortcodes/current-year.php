<?php

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'current_year', __NAMESPACE__ . '\current_year_shortcode_handler' );
/**
 * Shortcodes: [current_year]
 *
 * Shortcode to display the current year.
 *
 * Provides a dynamic means to render the current year after a copyright symbol.
 * Eliminates the need to manually update the current year on the website.
 *
 * @since 1.0.0
 *
 * @return string	The current year.
 */
function current_year_shortcode_handler(): string {
	return date( 'Y' );
}
