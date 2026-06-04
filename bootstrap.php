<?php
/**
 * Custom Functionality Plugin
 *
 * @package     rgadon107\CustomFunctionalityPlugin
 * @author      Robert A Gadon
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:     Custom Functionality
 * Plugin URI:      https://github.com/rgadon107/custom-functionality
 * Description:     A plugin that contains custom functions, scripts, and styles to modify the behavior of WordPress. Built off the `starter-plugin` package developed for KnowTheCode.io.
 * Version:         1.8.3
 * Requires WP:     6.9.4
 * Requires PHP:    8.3
 * Author:          Robert A Gadon
 * Author URI:      https://github.com/rgadon107/custom-functionality
 * Text Domain:     custom-functionality
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin;

/**
 * Gets this plugin's absolute directory path.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_directory(): string	{
	return __DIR__;
}

/*
 *  Registers the plugin with WordPress activation, deactivation, and uninstall hooks.
 *
 *  Note: Remove this function if using the 'central-hub' plugin instead to flush rewrites.
 *  Note: Activate this function if the plugin registers custom post-types, taxonomies, endpoints, or custom rewrites;
 * ** then keep the hooks and declare the function.  If it’s just “custom functions/scripts/styles” with no routing changes,
 * ** then deactivate (or remove) the rewrite hooks (no need to flush).
 *
 *  @since 1.0.0
 *
 *  @return void
 */
function register_plugin(): void
{

	register_activation_hook( __FILE__, __NAMESPACE__ . '\delete_rewrite_rules' );
	register_deactivation_hook( __FILE__, __NAMESPACE__ . '\delete_rewrite_rules' );
	register_uninstall_hook( __FILE__, __NAMESPACE__ . '\delete_rewrite_rules' );
}

/**
 * Clear/refresh WordPress rewrite rules (permalinks routing). See comments above
 *  on when to activate.
 *
 * Runs on activation/deactivation/uninstall via hook registrations above.
 *
 * @since 1.0.0
 *
 * @return void
 * @noinspection PhpUnused
 */
function delete_rewrite_rules(): void	{
	if ( function_exists( 'flush_rewrite_rules' ) ) {
		flush_rewrite_rules();
	}
}

/**
 * Gets this plugin's URL.
 *
 * @since  1.0.0
 * @since  1.8.0 Change paramater value in plugins_url() to satisfy PHP 8.1+ type requirements.
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_url(): string	{
	static $plugin_url;

	if ( empty( $plugin_url ) ) {
		$plugin_url = plugins_url( '', __FILE__ );
	}

	return $plugin_url;
}

/**
 * Checks if this plugin is in development mode.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return bool
 */
function _is_in_development_mode(): bool	{
	return defined( 'WP_DEBUG' ) && WP_DEBUG === true;
}

/**
 * Autoload the plugin's modules and files.
 *
 * @since 1.0.0	Initial release.
 * @since 1.8.0 Modify this function to serve as a module controller for the entire plugin.
 *
 * @return void
 */
function autoload_files(): void	{

	$plugin_files	= [
		'/includes/patterns/pattern-loader.php',
		'/src/asset/handler.php',
		'/src/shortcodes/expire-content.php',
		'/src/shortcodes/current-year.php',
		'/src/hooks.php',
	];

	foreach ( $plugin_files as $file ) {
		$path = _get_plugin_directory() . $file;
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}

	add_action( 'plugins_loaded', __NAMESPACE__ .'\\load_ninja_forms_integration' );
	/**
	 * Load Ninja Forms integrations only if the plugin is active.
	 *
	 * @since    1.0.7
	 * @return   void
	 */
	function load_ninja_forms_integration(): void	{
		if ( class_exists( 'Ninja_Forms' ) ) {
			$integration = _get_plugin_directory() . '/src/integrations/ninja-forms.php';
			if ( file_exists( $integration ) ) {
				require_once $integration;
			}
		}
	}
}

/**
 * Launch the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch(): void	{
	autoload_files();

// Uncomment 'Custom\register_plugin()' below if using `central-hub` plugin to flush rewrites.
// Remove a call to `rgadon107\CustomFunctionalityPlugin\register_plugin()` when using 'central-hub'.
//	Custom\register_plugin( __FILE__ );
	register_plugin();
}

launch();
