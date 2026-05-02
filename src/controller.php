<?php
/**
 * Main controller file.
 *
 * Handle the routing and loading of modular logic files stored within the `/src` directory.
 *
 */
namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

use function gardenClubOfMpls\CustomFunctionalityPlugin\_get_plugin_directory;

// Load the directory modules.
require_once __DIR__ . '/asset/handler.php';
require_once __DIR__ . '/shortcodes/expire-content.php';
require_once __DIR__ . '/shortcodes/current-year.php';

add_action( 'plugins_loaded', __NAMESPACE__ .'\\load_ninja_forms_integration' );
/**
 * Load Ninja Forms integrations only if the plugin is active.
 *
 * @since    1.0.7
 * @return   void
 */
function load_ninja_forms_integration(): void	{

	if ( class_exists( 'Ninja_Forms' ) ) {
		require_once _get_plugin_directory() . '/src/Integrations/ninja-forms.php';

	}
}

/**
 * Register the block patterns on the 'init' hook.
 */
add_action( 'init', __NAMESPACE__ . '\register_plugin_block_patterns' );

/**
 * Register custom block patterns.
 *
 * Process a manual list of pattern files from the plugin's
 * source directory and register them with WordPress.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_plugin_block_patterns(): void {

	$patterns_to_register = [
		'message-before-registration-start.php',
		'message-before-registration-start.php',
	];

	foreach ( $patterns_to_register as $file ) {

		$full_path = _get_plugin_directory() . '/patterns/' . $file;

		if ( ! file_exists( $full_path ) ) {
			continue;
		}

		$content = file_get_contents( $full_path );

		/**
		 * Initialize $title with the name 'Untitled Pattern'. If the regular expression does not find a value for
		 * 'Title' in the pattern file header, the plugin will return the initial value of the variable. This
		 * helps with pattern troubleshooting in the admin UI.	`
		 */
		$title = 'Untitled Pattern';

		if ( preg_match( '/Title:\s*(.*)$/mi', $content, $matches ) ) {
			$title = trim( $matches[1] );
		}

		/**
		 * Derive the slug from the filename (e.g., 'hero-header').
		 * wp_basename() is used for better cross-platform compatibility.
		 */
		$pattern_slug = wp_basename( $file, '.php' );

		/**
		 * Register the block pattern using PHP 8.0+ named arguments.
		 * The $pattern_slug is used as the unique identifier.
		 */
		register_block_pattern(
			pattern_name: $pattern_slug,
			pattern_properties: [
				'title'   => $title,
				'content' => $content,
			]
		);
	}
}
