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
 * @since 2.1.1 Refactor callback to load and loop through a custom configuration of script files.
 * @since 2.2.0 Enqueued 'member-photo-directory-loader'.
 *
 * @return void
 */
function enqueue_plugin_scripts(): void	{

	$events_page_id = 16223;
	$awards_banquet_page_id = 10424;
	$is_target_page = is_page($events_page_id) || is_page($awards_banquet_page_id);

	$scripts = [
		'nf-checkbox-toggle' => [
			'file' => '/assets/scripts/nf-checkbox-toggle.js',
			'deps' => ['jquery'],
			'in_footer' => true,
			'condition' => $is_target_page, // Evaluates to true/false
		],
		'nf-prevent-early-form-submit-while-using-return-key' => [
			'file' => '/assets/scripts/nf-prevent-early-form-submit-while-using-return-key.js',
			'deps' => [],
			'in_footer' => true,
			'condition' => true, // Load globally
		],
		'coblocks-accordion-prevent-vertical-scroll' => [
			'file' => '/assets/scripts/coblocks-accordion-prevent-vertical-scroll.js',
			'deps' => [],
			'in_footer' => true,
			'condition' => true,
		],
		'nf-scroll-fix' => [
			'file' => '/assets/scripts/nf-scroll-fix.js',
			'deps' => ['jquery'],
			'in_footer' => true,
			'condition' => true,
		],
		'member-photo-directory-loader' => [
			'file' => '/assets/scripts/member-photo-directory-loader.js',
			'deps' => [],
			'in_footer' => true,
			'condition' => true,
		],
	];

	$asset_paths = _get_asset_paths();

	foreach ( $scripts as $handle => $config ) {

		if ( !$config['condition'] ) {
			continue;
		}

		if ( file_exists($asset_paths['directory'] . $config['file'] ) ) {
			wp_enqueue_script(
				$handle,
				$asset_paths['url'] . $config['file'],
				$config['deps'],
				_get_asset_version( $config['file'] ),
				$config['in_footer']
			);
		}
	}
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_plugin_styles', 20 );
/**
 * Enqueues the plugin's styles.
 *
 * @since 1.2.0	Enqueued 'event-registration-notice-styles'
 * @since 1.5.0	Enqueued 'ninja-form-email-signup-form-styles'
 * @since 1.6.0	Refactored callback and enqueued 'coblocks-accordian-fix'.
 * @since 2.2.0 Enqueued 'member-photo-directory-viewer-styles'
 *
 * @return void
 */
function enqueue_plugin_styles(): void {
	$styles = [
		'event-registration-notice-styles'    	=>	'/assets/styles/event-notices.css',
		'ninja-form-email-signup-form-styles' 	=>	'/assets/styles/ninja-form-styles.css',
		'coblocks-accordion-styles'           	=>	'/assets/styles/coblocks-accordion-styles.css',
		'color-variables'					  	=>	'/assets/styles/color-variables.css',
		'member-photo-directory-styles'			=>	'/assets/styles/member-photo-directory-styles.css'
	];

	$asset_paths = _get_asset_paths();

	foreach ( $styles as $handle => $file ) {
		// Defensive check: Verify the file exists on disk before enqueuing
		if ( file_exists( $asset_paths['directory'] . $file ) ) {
			wp_enqueue_style(
				$handle,
				$asset_paths['url']. $file,
				[],
				_get_asset_version( $file )
			);
		}
	}
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_block_editor_scripts' );
/**
 * Enqueues scripts for the WordPress Block Editor.
 *
 * @since 2.3.1
 *
 * @return void
 */
function enqueue_block_editor_scripts(): void {
	$screen = get_current_screen();

	// Evaluate target editor post-types
	$is_auction_item = $screen && 'auction_item' === $screen->post_type;

	$editor_scripts = [
		'auction-item-meta-panel' => [
			'file'      => '/assets/scripts/auction-item-meta-panel.js',
			'deps'      => [
				'wp-plugins',
				'wp-edit-post',
				'wp-components',
				'wp-core-data',
				'wp-data',
				'wp-element',
			],
			'in_footer' => true,
			'condition' => $is_auction_item,
		],
		// Future editor sidebar/block module scripts go here.
	];

	$asset_paths = _get_asset_paths();

	foreach ( $editor_scripts as $handle => $config ) {

		if ( ! $config['condition'] ) {
			continue;
		}

		if ( file_exists( $asset_paths['directory'] . $config['file'] ) ) {
			wp_enqueue_script(
				$handle,
				$asset_paths['url'] . $config['file'],
				$config['deps'],
				_get_asset_version( $config['file'] ),
				$config['in_footer']
			);
		}
	}
}

/**
 * Gets reusable plugin asset paths.
 *
 * @since 2.3.1
 *
 * @return array{
 *     directory: string,
 *     url: string
 * }
 */
function _get_asset_paths(): array {
	static $asset_paths = null;

	if ( null === $asset_paths ) {
		$asset_paths = [
			'directory' => _get_plugin_directory(),
			'url'       => _get_plugin_url(),
		];
	}

	return $asset_paths;
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
