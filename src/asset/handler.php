<?php
/**
 * Asset Handler.
 *
 * @package     gardenClubOfMpls\CustomFunctionalityPlugin\Asset
 * @since       1.0.0
 * @author      Robert Gadon
 * @link        https://github.com/rgadon107/custom-functionality
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
 * @since 2.4.0
 * 	- Enqueued 'custom-event-accordion-script' conditionally on single 'events' posts.
 *  - Enqueue 'custom-event-accordion-script' on single event views, event archives, event category archives, and any singular page containing [gcm_events_calendar].
 *
 * @return void
 */
function enqueue_plugin_scripts(): void {

	$events_page_id         = 16223;
	$awards_banquet_page_id = 10424;
	$is_target_page         = is_page( $events_page_id ) || is_page( $awards_banquet_page_id );

	$current_post             = get_post();
	$has_events_shortcode     = $current_post instanceof \WP_Post && has_shortcode( $current_post->post_content, 'gcm_events_calendar' );
	$is_event_accordion_page  = is_singular( 'events' ) || is_post_type_archive( 'events' ) || is_tax( 'event-category' ) || $has_events_shortcode;

	$scripts = [
		'nf-checkbox-toggle' => [
			'file'      => '/assets/scripts/nf-checkbox-toggle.js',
			'deps'      => [ 'jquery' ],
			'in_footer' => true,
			'condition' => $is_target_page, // Evaluates to true/false.
		],
		'nf-prevent-early-form-submit-while-using-return-key' => [
			'file'      => '/assets/scripts/nf-prevent-early-form-submit-while-using-return-key.js',
			'deps'      => [],
			'in_footer' => true,
			'condition' => true, // Load globally.
		],
		'coblocks-accordion-prevent-vertical-scroll' => [
			'file'      => '/assets/scripts/coblocks-accordion-prevent-vertical-scroll.js',
			'deps'      => [],
			'in_footer' => true,
			'condition' => true,
		],
		'nf-scroll-fix' => [
			'file'      => '/assets/scripts/nf-scroll-fix.js',
			'deps'      => [ 'jquery' ],
			'in_footer' => true,
			'condition' => true,
		],
		'member-photo-directory-loader' => [
			'file'      => '/assets/scripts/member-photo-directory-loader.js',
			'deps'      => [],
			'in_footer' => true,
			'condition' => true,
		],
		'accordion-toggle-handler' => [
			'file'      => '/assets/scripts/accordion-toggle-handler.js',
			'deps'      => [],
			'in_footer' => true,
			'condition' => true,
		],
		'custom-event-accordion-script' => [
			'file'      => '/assets/scripts/event-accordion.js',
			'deps'      => [],
			'in_footer' => true,
			'condition' => $is_event_accordion_page,
		],
	];

	$asset_paths = _get_asset_paths();

	foreach ( $scripts as $handle => $config ) {

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

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_plugin_styles', 20 );
/**
 * Enqueues the plugin's styles.
 *
 * @since 1.2.0 Enqueued 'event-registration-notice-styles'.
 * @since 1.5.0 Enqueued 'ninja-form-email-signup-form-styles'.
 * @since 1.6.0 Refactored callback and enqueued 'coblocks-accordian-fix'.
 * @since 2.2.0 Enqueued 'member-photo-directory-viewer-styles'.
 * @since 2.4.0 Added 'color-variables' dependency to 'event-registration-notice-styles' and enqueued 'custom-event-accordion-style' conditionally on single 'events' posts.
 *
 * @return void
 */
function enqueue_plugin_styles(): void {
	$styles = [
		'color-variables' => [
			'file' => '/assets/styles/color-variables.css',
			'deps' => [],
		],
		'event-registration-notice-styles' => [
			'file' => '/assets/styles/event-notices.css',
			'deps' => [ 'color-variables' ],
		],
		'ninja-form-email-signup-form-styles' => [
			'file' => '/assets/styles/ninja-form-styles.css',
			'deps' => [],
		],
		'coblocks-accordion-styles' => [
			'file' => '/assets/styles/coblocks-accordion-styles.css',
			'deps' => [],
		],
		'member-photo-directory-styles' => [
			'file' => '/assets/styles/member-photo-directory-styles.css',
			'deps' => [],
		],
		'custom-event-accordion-style' => [
			'file'      => '/assets/styles/event-accordion.css',
			'deps'      => [],
		],
	];

	$asset_paths = _get_asset_paths();

	foreach ( $styles as $handle => $config ) {

		if ( isset( $config['condition'] ) && ! $config['condition'] ) {
			continue;
		}

		// Defensive check: Verify the file exists on disk before enqueuing.
		if ( file_exists( $asset_paths['directory'] . $config['file'] ) ) {
			wp_enqueue_style(
				$handle,
				$asset_paths['url'] . $config['file'],
				$config['deps'],
				_get_asset_version( $config['file'] )
			);
		}
	}
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_block_editor_scripts' );
/**
 * Enqueues scripts for the WordPress Block Editor.
 *
 * @since 2.3.1 Initial release.
 * @since 2.4.0 Added configuration for 'event-meta-panel'.
 *
 * @return void
 */
function enqueue_block_editor_scripts(): void {
	$screen = get_current_screen();

	// Evaluate target editor post-types.
	$is_auction_item = $screen && 'auction_item' === $screen->post_type;
	$is_events       = $screen && 'events' === $screen->post_type;

	$editor_scripts = [
		'auction-item-meta-panel' => [
			'file'      => '/assets/scripts/auction-item-meta-panel.js',
			'deps'      => [
				'wp-plugins',
				'wp-editor',
				'wp-components',
				'wp-core-data',
				'wp-data',
				'wp-element',
			],
			'in_footer' => true,
			'condition' => $is_auction_item,
		],
		'event-meta-panel' => [
			'file'      => '/assets/scripts/event-meta-panel.js',
			'deps'      => [
				'wp-plugins',
				'wp-editor',
				'wp-components',
				'wp-core-data',
				'wp-data',
				'wp-element',
			],
			'in_footer' => true,
			'condition' => $is_events,
		],
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

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_scripts' );
/**
 * Enqueues scripts for WordPress admin list tables and screens.
 *
 * Evaluates screen-specific conditions against a configuration array so multiple
 * CPT admin scripts can be conditionally registered and enqueued via a single loop.
 *
 * @since 2.4.1 Intial release. Refactored to use a configuration array and loop for multi-CPT scalability.
 *
 * @param string $hook_suffix The current admin page hook suffix.
 * @return void
 */
function enqueue_admin_scripts( string $hook_suffix ): void {
	// Only evaluate list-table scripts on edit.php.
	if ( 'edit.php' !== $hook_suffix ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}

	// Evaluate target admin list-table post types.
	$is_events_list  = 'events' === $screen->post_type;
	$is_members_list = 'members' === $screen->post_type; // Example future CPT.

	$admin_scripts = [
		'gcm-events-quick-edit'  => [
			'file'      => '/assets/scripts/events-quick-edit.js',
			'deps'      => [ 'jquery', 'inline-edit-post' ],
			'in_footer' => true,
			'condition' => $is_events_list,
		],
		'gcm-members-quick-edit' => [
			'file'      => '/assets/scripts/members-quick-edit.js',
			'deps'      => [ 'jquery', 'inline-edit-post' ],
			'in_footer' => true,
			'condition' => $is_members_list,
		],
	];

	$asset_paths = _get_asset_paths();

	foreach ( $admin_scripts as $handle => $config ) {
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
 * @since 2.3.1 Initial release.
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
function _get_asset_version( string $relative_path ): bool|int {
	return filemtime( _get_plugin_directory() . $relative_path );
}

/**
 * Appends a file modification timestamp to the absolute URL of an uploaded file as a version query parameter.
 *
 * @since 2.3.1 Initial release.
 *
 * @param string $file_url The public URL of the uploaded media file.
 *
 * @return string The URL with ?v={timestamp} appended, or original URL if file is not found or target mismatched.
 */
function _get_media_asset_version( string $file_url ): string {
	// Only target files containing 'GCM-Photo-Directory' in the URL/filename.
	if ( ! str_contains( $file_url, 'GCM-Photo-Directory' ) ) {
		return $file_url;
	}

	$upload_dir = wp_upload_dir();
	$base_url   = $upload_dir['baseurl'];
	$base_dir   = $upload_dir['basedir'];

	if ( str_starts_with( $file_url, $base_url ) ) {
		$relative_path = str_replace( $base_url, '', $file_url );
		$absolute_path = $base_dir . $relative_path;

		if ( file_exists( $absolute_path ) ) {
			$timestamp = filemtime( $absolute_path );
			return add_query_arg( 'v', $timestamp, $file_url );
		}
	}

	return $file_url;
}
