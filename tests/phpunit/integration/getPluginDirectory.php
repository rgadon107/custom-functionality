<?php
/**
 * Tests _get_plugin_directory().
 *
 * @package     rgadon107\CustomFunctionalityPlugin\Tests\PHP\Integration
 * @since       1.0.0
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GNU-2.0+
 */

namespace rgadon107\CustomFunctionalityPlugin\Tests\PHP\Integration;

use function rgadon107\CustomFunctionalityPlugin\_get_plugin_directory;
use function rgadon107\CustomFunctionalityPlugin\Tests\PHP\get_plugin_root_dir;

/**
 * Class Tests_GetPluginDirectory
 *
 * @package rgadon107\CustomFunctionalityPlugin\Tests\PHP\Integration
 */
class Tests_GetPluginDirectory extends Test_Case {

	/**
	 * Test _get_plugin_directory() should return the plugin's root directory.
	 */
	public function test__get_plugin_directory_should_run_plugin_directory() {
		$this->assertStringEndsWith( 'custom-functionality', _get_plugin_directory() );
		$this->assertSame( rtrim( get_plugin_root_dir(), DIRECTORY_SEPARATOR ), _get_plugin_directory() );
	}
}
