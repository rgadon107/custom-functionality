<?php
/**
 * A sandbox for testing code.
 *
 * Runs only when the environment mode is 'local' or 'development'.
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Sandbox;

add_action( 'init', __NAMESPACE__ . '\register_custom_meta');
/**
 * Register custom post_meta.
 *
 * @return void
 */
function register_custom_meta(): void {

	register_meta( 'post', 'projectslug_mood', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'sanitize_callback' => 'wp_strip_all_tags'
	) );

}

/**
 * Increase the native Custom Fields dropdown limit for local testing.
 */
add_filter( 'postmeta_form_limit', function( int $limit ): int {
	return 200; // Increase from default 30 to 200
} );
