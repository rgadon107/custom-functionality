<?php
/**
 * Bootstraps the Plugin's Unit Tests.
 *
 * @package     rgadon107\CustomFunctionalityPlugin\Tests\PHP\Unit
 * @since       1.0.0
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GNU-2.0+
 */

namespace rgadon107\CustomFunctionalityPlugin\Tests\PHP\Unit;

use function rgadon107\CustomFunctionalityPlugin\Tests\PHP\load_composer_autoloader;

/**
 * Gets the unit test's root directory.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_test_root_dir() {
	return __DIR__;
}

/**
 * Load the test suite's dependencies.
 *
 * @since 1.0.0
 *
 * @return void
 */
function load_dependencies() {
	require_once dirname( __DIR__ ) . '/functions.php';
	load_composer_autoloader();

	require_once dirname( __DIR__ ) . '/test-case-trait.php';
	require_once get_test_root_dir() . '/class-test-case.php';
}

load_dependencies();
