<?php
/**
 * Main controller file.
 *
 * Handle the routing and loading of modular logic files stored within the `/src` directory.
 *
 */
namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

// Load the directory modules.
require_once __DIR__ . '/asset/handler.php';
require_once __DIR__ . '/shortcodes/expire-content.php';

/**
 * Register the plugin block patterns.
 *
 * @since 1.0.0
 *
 * @return void
 */
add_action('init', function (): void {

	$pattern_path = __DIR__ . '/patterns/message-before-registration-start.php';

	if (file_exists($pattern_path)) {
		register_block_pattern(
			'registration-closed-message',
			array(
				'title' 	=>  'Registration Closed Notice',
				'content' 	=> 	file_get_contents($pattern_path)
			)
		);
	}
});
