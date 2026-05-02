<?php

/**
 * Custom callbacks hooked to Ninja Forms actions or filters.
 *
 * @package     gardenClubOfMpls\CustomFunctionalityPlugin\Source\Integrations
 * @since       1.0.7
 * @author      Robert Gadon
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GPL-2.0+
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('ninja_forms_submit_data', __NAMESPACE__ .'\\cleanup_form_submission_data');
/**
 * Ninja Forms Integration - Form Data Cleanup
 *
 * Strip whitespace, title case names, and lowercase email addresses before form submission.
 * This custom callback applies to _all_ active Ninja Forms with field keys that contain the term `name`, and `email`.
 *
 * @since 	1.0.7
 *
 * @param 	array $form_data The original form submission data.
 * @return 	array $form_data The sanitized form submission data returned on submit.
 */
function cleanup_form_submission_data(array $form_data): array	{

	if ( !isset( $form_data['fields']) || !is_array( $form_data['fields'] ) ) {
		return $form_data;
	}

	foreach ( $form_data['fields'] as $id => $field ) {

		$value 	= $field['value'] ?? '';
		$key 	= strtolower( $field['key'] ?? '' );

		// Ensure we only touch strings
		if ( !is_string($value) || empty($value) ) {
			continue;
		}

		// Universal Trim (Remove standard spaces, tabs, and non-breaking spaces.)
		$value = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $value);

		// Title Case for Names (Handling hyphens/apostrophes)
		if (str_contains( $key, 'name') ) {
			$value = mb_strtolower( $value );
			$delimiters = " \t\r\n\f\v-'";
			$value = ucwords( $value, $delimiters );
		}

		// Lowercase for Emails
		if (str_contains( $key, 'email') ) {
			$value = mb_strtolower( $value );
		}

		// Update the array
		$form_data['fields'][$id]['value'] = $value;
	}

	return $form_data;
}
