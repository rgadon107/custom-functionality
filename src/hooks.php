<?php

/**
 * Custom callbacks hooked to WordPress actions or filters.
 *
 * @package     gardenClubOfMpls\CustomFunctionalityPlugin\Source
 * @since       1.0.0
 * @author      Robert Gadon
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GPL-2.0+
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'post_password_expires', __NAMESPACE__ . '\modify_cookie_expiration' );
/**
 * Make the protected-page password cookie a session cookie.
 * * Require visitors to reenter the password to access member-restricted content.
 *
 * @since 1.0.0
 *
 * @param 	int $expires Expiration timestamp passed to set cookie.
 * @return 	int Zero ( 0 ) to create a session cookie (expires when browser session ends).
 */
function modify_cookie_expiration( int $expires ): int {
	return 0;
}
