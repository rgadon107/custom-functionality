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
 * Filter _all_ active Ninja Forms with field keys that contain the term `name`, `email`, address`, `city`, and `county`.
 *
 * @since 	1.0.7	Filter field keys that contain the terms `name`, and `email`.
 * @since	1.0.8	Filter field keys that contain the terms `zip`, `address`, `city`, and `county`.
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

		// Sorting Machine: Route data based on 'fuzzy' key matching
		if ( str_contains( $key, 'name' ) ) {
			// Case 1: Names (Title case)
			$value = mb_strtolower( $value );
			$value = ucwords( $value, " \t\r\n\f\v-'" );
		} elseif ( str_contains( $key, 'email' ) ) {
			// Case 2: Emails (Lowercase)
			$value = mb_strtolower( $value );
		} elseif ( str_contains( $key, 'zip' ) ) {
			// Case 3: Zip Codes ( first 5 characters only )
			$value = substr($value, 0, 5);
		} elseif ( ( str_contains( $key, 'address' ) || str_contains( $key, 'city' ) || str_contains( $key, 'county' ) ) ) {
			// Case 4: Address , City, and County ( Title case and expand street abbreviations )
			$value = standardize_location_data( $value, $key );
		}
		// Update the array
		$form_data['fields'][$id]['value'] = $value;
	}

	return $form_data;
}

/**
 * Specialized helper function to filter address, city, and county fields.
 *
 * @since 1.0.8
 *
 * @param string $value The value to be filtered.
 * @param string $key The field key.
 * @return string The filtered value.
 */
function standardize_location_data( string $value, string $key ): string {

	// Initial cleanup: Lowercase then Title Case
	$value = mb_strtolower( $value );
	$value = ucwords( $value, " \t\r\n\f\v-'" );

	// Street Suffix Expansion (Only for the 'address' field)
	if ( str_contains( $key, 'address' ) ) {
		$suffixes = [
			'Dr'    => 'Drive',
			'Ln'    => 'Lane',
			'Tr'    => 'Trail',
			'Cir'   => 'Circle',
			'Av'    => 'Avenue',
			'Ave'   => 'Avenue',
			'Ct'    => 'Court',
			'Rd'    => 'Road',
			'Pkwy'  => 'Parkway',
			'Bl'    => 'Boulevard',
			'St'    => 'Street',
			'Terr'  => 'Terrace',
			'Pl'    => 'Place',
			'Wy'    => 'Way',
			'Path'  => 'Path',
			'Hwy'   => 'Highway',
			'Sq' 	=> 'Square',
			'Trce'	=> 'Trace',
			'S'		=> 'South',
			'So'	=> 'South',
			'Sw'	=> 'Southwest',
			'Se'	=> 'Southeast',
			'N'		=> 'North',
			'No'	=> 'North',
			'Ne'	=> 'Northeast',
			'Nw'	=> 'Northwest',
			'E'		=> 'East',
			'W'		=> 'West',

		];

		foreach ( $suffixes as $abbr => $full ) {
			/**
			 * Updated Regex:
			 * \b      : Start at a word boundary
			 * $abbr   : Match the abbreviation (e.g., 'No')
			 * \b      : Ensure the word 'No' ends here (prevents matching 'North')
			 * \.?     : Swallows the period if it exists
			 */
			$pattern = '/\b' . preg_quote( $abbr, '/' ) . '\b\.?/';
			$value   = preg_replace( $pattern, $full, $value );
		}
	}

	return $value;
}
