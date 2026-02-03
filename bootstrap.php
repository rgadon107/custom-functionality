<?php
/**
 * Starter Plugin
 *
 * @package     spiralWebDb\StarterPlugin
 * @author      Robert A Gadon
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:     Starter Plugin
 * Plugin URI:      https://github.com/spiralWebDb/starter-plugin
 * Description:     A WordPress plugin boilerplate that emphasizes code quality and provides you a quick start to your custom plugin development project.
 * Version:         1.0.0
 * Requires WP:     5.0
 * Requires PHP:    5.6
 * Author:          Robert A Gadon
 * Author URI:      https://spiralWebDb.com
 * Text Domain:     custom-functionality
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace spiralWebDb\StarterPlugin;

/**
 * Gets this plugin's absolute directory path.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_directory() {
	return __DIR__;
}

/*
 *  Registers the plugin with WordPress activation, deactivation, and uninstall hooks.
 *
 *  Note: Remove this function if using the 'central-hub' plugin instead to flush rewrites.
 *
 *  @since 1.0.0
 *
 *  @return void
 */
function register_plugin() {

	register_activation_hook( __FILE__, __NAMESPACE__ . '\delete_rewrite_rules' );
	register_deactivation_hook( __FILE__, __NAMESPACE__ . '\delete_rewrite_rules' );
	register_uninstall_hook( __FILE__, __NAMESPACE__ . '\delete_rewrite_rules' );
}

/**
 * Gets this plugin's URL.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_url() {
	static $plugin_url;

	if ( empty( $plugin_url ) ) {
		$plugin_url = plugins_url( null, __FILE__ );
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
function _is_in_development_mode() {
	return defined( WP_DEBUG ) && WP_DEBUG === true;
}

/**
 * Autoload the plugin's files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function autoload_files() {
	$files = [
		// add the list of files to load here.
	];

	foreach ( $files as $file ) {
		require __DIR__ . '/src/' . $file;
	}
}

/**
 * Launch the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	autoload_files();

// Uncomment 'Custom\register_plugin()' below if using `central-hub` plugin to flush rewrites.
// Remove call to 'spiralWebDb\StarterPlugin\register_plugin()' when using 'central-hub'.
//	Custom\register_plugin( __FILE__ );
	register_plugin();
}

launch();
