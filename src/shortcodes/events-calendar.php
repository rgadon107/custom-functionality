<?php
/**
 * Shortcode: Events Calendar Section.
 *
 * Registers and renders the `[gcm_events_calendar]` shortcode, which
 * buffers and parses `/src/patterns/events-calendar.php` server-side
 * via `do_blocks()` so the Events Query Loop renders cleanly on standard pages.
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage Source\Shortcodes
 * @since      2.4.0
 */

declare( strict_types = 1 );

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Shortcodes;

add_shortcode( 'gcm_events_calendar', __NAMESPACE__ . '\render_events_calendar_shortcode' );
/**
 * Renders the Events Calendar block pattern markup on the front end.
 *
 * @since 2.4.0 Initial release
 *
 * @return string Rendered HTML for the Events Calendar section.
 */
function render_events_calendar_shortcode(): string {
	$pattern_file = dirname( __DIR__ ) . '/patterns/events-calendar.php';
	if ( ! file_exists( $pattern_file ) ) {
		return '';
	}

	ob_start();
	include $pattern_file;
	$markup = (string) ob_get_clean();

	return do_blocks( $markup );
}
