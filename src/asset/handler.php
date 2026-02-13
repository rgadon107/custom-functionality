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

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_plugin_scripts', 20 );
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
