<?php
/**
 * Custom callbacks hooked to WordPress actions or filters.
 *
 * @package     rgadon107\CustomFunctionalityPlugin\Source
 * @since       1.0.0
 * @author      Robert Gadon <@rgadon107>
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GPL-2.0+
 */

namespace rgadon107\CustomFunctionalityPlugin\Source;

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'post_password_expires', __NAMESPACE__ . '\modify_cookie_expiration' );

/**
 * Make the post-password cookie a session cookie.
 *
 * @since 1.0.0
 *
 * @param int $expires Expiration timestamp passed to setcookie().
 * @return int 0 to create a session cookie (expires when browser session ends).
 */
function modify_cookie_expiration( int $expires ): int {
	return 0;
}
