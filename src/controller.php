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
		require_once _get_plugin_directory() . '/src/integrations/ninja-forms.php';

	}
}
