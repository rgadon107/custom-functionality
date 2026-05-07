<?php
/**
 * Register all custom block pattern categories and block patterns called by this plugin.
 *
 */
namespace gardenClubOfMpls\CustomFunctionalityPlugin\Includes;

use function gardenClubOfMpls\CustomFunctionalityPlugin\_get_plugin_directory;

add_action( 'init', __NAMESPACE__ . '\\register_pattern_categories', 5 );
add_action( 'init', __NAMESPACE__ . '\\register_plugin_block_patterns' );
/**
 * Register custom block pattern categories for use in the Gutenberg editor.
 *
 * The function defines an array of categories, each with a unique slug and label.
 * It then iterates through the array and registers each category using `register_block_pattern_category`.
 *
 * @since 1.8.0
 *
 * @return void
 */
function register_pattern_categories(): void	{
	$categories = array(
		'accordion-meeting-topics-table' 	=> array('label' => __('Accordion: Meeting Topics Table', 'custom-functionality')),
		'message-before-registration-start' => array('label' => __('Message: Before Registration Start', 'custom-functionality')),
		'message-after-registration-stop' 	=> array('label' => __('Message: After Registration Stop', 'custom-functionality')),
	);

	foreach ($categories as $slug => $settings) {
		register_block_pattern_category($slug, $settings);
	}
}

/**
 * Register custom block patterns.
 *
 * Process a configuration list of design pattern files stored in the plugin's
 * `src/patterns` directory and register them with WordPress.
 *
 * @since 1.0.0
 * @return void
 */
function register_plugin_block_patterns(): void {

	$patterns_to_register = [
		'message-before-registration-start.php',
		'message-after-registration-stop.php',
		'accordion-meeting-topics.php',
		];

	foreach ( $patterns_to_register as $file ) {
		load_and_register_pattern( $file );
	}
}

/**
 * Helper: Read the file, parse the design pattern header, and call `register_block_pattern()`.
 *
 * @param string $file The filename of the pattern file to load.
 * @return void
 */
function load_and_register_pattern( string $file ): void {

	$full_path = _get_plugin_directory() . '/src/patterns/' . $file;

	if ( ! file_exists( $full_path ) ) {
		return;
	}

	$content = file_get_contents( $full_path );

	/**
	 * Set initial values for the $title and $categories variables. If the regular expression does not find a value for
	 * either variable in the pattern file header, the plugin will return their initial value. This
	 * helps with pattern troubleshooting in the admin UI.	`
	 */
	$title   = 'Untitled Pattern';
	$categories = [ 'text' ];

	// Parse the 'Title' header from the file
	if ( preg_match( '/Title:\s*(.*)$/mi', $content, $matches ) ) {
		$title = trim($matches[1]);
	}

	// Parse the 'Categories' header (comma-separated string to array)
	if ( preg_match( '/Categories:\s*(.*)$/mi', $content, $matches ) ) {
		// Convert "text, featured, gcm" into ['text', 'featured', 'gcm']
		$categories = array_map( 'trim', explode( ',', $matches[1] ) );
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
		$pattern_slug,
		[
			'title'   		=> $title,
			'categories'	=> $categories,
			'content' 		=> $content,
		]
	);
}
