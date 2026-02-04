<?php
/**
 * Tests _get_plugin_url().
 *
 * @package     rgadon107\CustomFunctionalityPlugin\Tests\PHP\Integration
 * @since       1.0.0
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GNU-2.0+
 */

namespace rgadon107\CustomFunctionalityPlugin\Tests\PHP\Integration;

use function rgadon107\CustomFunctionalityPlugin\_get_plugin_url;

/**
 * Class Tests_GetPluginUrl
 *
 * @package rgadon107\CustomFunctionalityPlugin\Tests\PHP\Integration
 */
class Tests_GetPluginUrl extends Test_Case {

	/**
	 * Test _get_plugin_url() should return the plugin's URL.
	 */
	public function test__get_plugin_url_should_run_plugin_url() {
		$this->assertStringEndsWith( 'plugins/custom-functionality', _get_plugin_url() );
	}
}
