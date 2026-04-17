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

add_filter( 'http_request_args', __NAMESPACE__. '\filter_http_request_timeout', 10, 2 );
/**
 * Increase the default HTTP request timeout to handle latent responses from
 * Constant Contact during household membership applications.
 *
 * @since 1.0.0
 *
 * @param array<string, mixed> $http_request_args   The parsed arguments for the HTTP request.
 * @param string               $url                 The request URL.
 * @return array<string, mixed> 					The modified HTTP request arguments.
 */
function filter_http_request_timeout( array $http_request_args, string $url ): array
{

	if ( str_contains( $url, 'constantcontact.com' ) ) {

		// Increase the http request timeout from 5 ( default ) to 20 seconds.
		$http_request_args['timeout'] = 20;
	}

	return $http_request_args;
}
