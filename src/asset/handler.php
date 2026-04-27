<?php
/**
 * Asset Handler.
 *
 * @package     gardenClubOfMpls\CustomFunctionalityPlugin\Asset
 * @since       1.0.0
 * @author      Robert Gadon
 * @link       	https://github.com/rgadon107/custom-functionality
 * @license     GNU-2.0+
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Asset;

use function gardenClubOfMpls\CustomFunctionalityPlugin\_get_plugin_directory;
use function gardenClubOfMpls\CustomFunctionalityPlugin\_get_plugin_url;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_plugin_scripts', 20 );
/**
 * Enqueues the plugin's script(s).
 *
 * @since 1.0.0
 *
 * @return void
 */
function enqueue_plugin_scripts(): void {

	$events_page_id         = 16223;
	$awards_banquet_page_id = 10424;

	if ( is_page( [ $events_page_id, $awards_banquet_page_id ] ) ) {

		$file = '/assets/scripts/nf-checkbox-toggle.js';

		wp_enqueue_script(
			'nf-checkbox-toggle',
			_get_plugin_url() . $file,
			[ 'jquery' ],
			_get_asset_version( $file ),
			true
		);
	}

	$file = '/assets/scripts/nf-prevent-early-form-submit-while-using-return-key.js';

	wp_enqueue_script(
		'nf-prevent-early-form-submit-while-using-return-key',
		_get_plugin_url() . $file,
		[],
		_get_asset_version( $file ),
		true
	);
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_plugin_styles', 20 );
/**
 * Enqueues the plugin's styles.
 *
 * @since 1.2.0	Enqueued 'event-registration-notice-styles'
 * @since 1.5.0	Enqueued 'ninja-form-email-signup-form-styles'
 * @since 1.6.0	Refactored callback and enqueued 'coblocks-accordian-fix'.
 *
 * @return void
 */
function enqueue_plugin_styles(): void {
	$styles = [
		'event-registration-notice-styles'    => '/assets/styles/event-notices.css',
		'ninja-form-email-signup-form-styles' => '/assets/styles/ninja-form-styles.css',
		'coblocks-accordian-fix'              => '/assets/styles/coblocks-accordian-fix.css',
	];

	$plugin_dir = _get_plugin_directory();
	$plugin_url = _get_plugin_url();

	foreach ( $styles as $handle => $file ) {
		// Defensive check: Verify the file exists on disk before enqueuing
		if ( file_exists( $plugin_dir . $file ) ) {
			wp_enqueue_style(
				$handle,
				$plugin_url . $file,
				[],
				_get_asset_version( $file )
			);
		}
	}
}

/**
 * Gets the asset file's version number by using its modification timestamp.
 *
 * @since 1.0.0
 *
 * @param string $relative_path Relative path to the asset file.
 *
 * @return bool|int
 */
function _get_asset_version( string $relative_path ): bool|int	{
	return filemtime( _get_plugin_directory() . $relative_path );
}
