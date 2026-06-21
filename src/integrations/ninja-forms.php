<?php /** @noinspection ALL */

/**
 * Custom callbacks hooked to Ninja Forms actions or filters.
 *
 * @package     gardenClubOfMpls\CustomFunctionalityPlugin\Source\Integrations
 * @since       1.7.0
 * @author      Robert Gadon
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GPL-2.0+
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('ninja_forms_submit_data', __NAMESPACE__ .'\\cleanup_form_submission_data', 999);
/**
 * Ninja Forms Integration - Form Data Cleanup
 *
 * Strip whitespace, title case names, and lowercase email addresses before form submission.
 * Filter _all_ active Ninja Forms with field keys that contain the term `name`, `email`, address`, `city`, and `county`.
 *
 * @since 	1.7.0	Filter field keys that contain the terms `name`, and `email`.
 * @since	1.7.2	Filter field keys that contain the terms `zip`, `address`, `city`, and `county`.
 * @since	1.7.3	Filter field keys that contain the term `phone`.
 * @since 	1.8.3	Added data integrity enforcement when the 'county' field returns a value of 'none_of_these'.
 *
 * @param 	array $form_data The original form submission data.
 * @return 	array $form_data The sanitized form submission data returned on submit.
 */
function cleanup_form_submission_data(array $form_data): array	{

	if ( !isset( $form_data['fields']) || !is_array( $form_data['fields'] ) ) {
		return $form_data;
	}

	foreach ( $form_data['fields'] as $id => $field ) {

		$key_input 	=  $field['key'] ?? '';
		if ( !is_string( $key_input ) ) {
			continue;
		}
		$key_lowercase = strtolower( $key_input );

		$value_input 	= $field['value'] ?? '';
		if ( !is_string( $value_input  ) || empty( $value_input ) ) {
			continue;
		}

		// Data Integrity Enforcement: If applicant submits a county outside the MSP metro area, force the dropdown to match.
		if ( str_contains( $key_lowercase, 'county_outside_msp_metro' ) ) {
			// Find the companion dropdown field in the payload and ensure its value matches the logic
			foreach ( $form_data['fields'] as $search_id => $search_field ) {
				if ( str_contains( strtolower( $search_field['key'] ?? '' ), 'county_1780529684228' ) ) {
					$form_data['fields'][$search_id]['value'] = 'none_of_these';
					break;
				}
			}
		}

		// Universal Trim (Remove standard spaces, tabs, and non-breaking spaces.)
		$value_trimmed = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $value_input );

		// Sorting Machine using PHP 8 `match` expression
		$value_trimmed = match ( true ) {
			// Names: Title Case 'name' fields
			str_contains($key_lowercase, 'name') => ucwords(mb_strtolower($value_trimmed), " \t\r\n\f\v-'"),

			// Emails: Lowercase 'email' field(s)
			str_contains($key_lowercase, 'email') => mb_strtolower($value_trimmed),

			// Zip Codes: Truncate to first 5 characters for Zapier compatibility
			str_contains($key_lowercase, 'zip') => substr($value_trimmed, 0, 5),

			/**
			 * Phone Numbers: Strip the '+1 ' prefix (indices 0, 1, 2)
			 * Returns (XXX) XXX-XXXX
			 */
			str_contains($key_lowercase, 'phone') => substr($value_trimmed, 3, 16),

			// Render full address street abbreviations; title case 'city' and 'county' fields
			str_contains($key_lowercase, 'address') ||
			str_contains($key_lowercase, 'city') ||
			str_contains($key_lowercase, 'county') => standardize_location_data($value_trimmed, $key_lowercase),

			// Default: If match returns false, leave $value_trimmed unchanged
			default => $value_trimmed,
		};

		// Update the array
		$form_data['fields'][$id]['value'] = $value_trimmed;
	}

	return $form_data;
}

/**
 * Specialized helper function to filter address, city, and county fields.
 *
 * @since 1.7.2
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
			'Lm'	=> 'Lane',
			'Lsne'	=> 'Lane',
			'Tr'    => 'Trail',
			'Cir'   => 'Circle',
			'Av'    => 'Avenue',
			'Ave'   => 'Avenue',
			'Ct'    => 'Court',
			'Rd'    => 'Road',
			'Pkwy'  => 'Parkway',
			'Bl'    => 'Boulevard',
			'St'    => 'Street',
			'Ter'   => 'Terrace',
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
			'Cv'	=> 'Curve',
			'Crv'	=> 'Curve',
			'Curv'	=> 'Curve',

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

add_action( 'ninja_forms_loaded', __NAMESPACE__ . '\\load_nf_filter_to_modify_stripe_checkout_expiration' );
/**
 *	Load the filter 'ninja_forms_stripe_checkout_session_create_params' and registered callback on the 'ninja_forms_loaded' hook.
 *
 *  Once Ninja Forms and all its add-on plugin extensions are loaded, then call the filter and its registered callbacks.
 *
 * @ since 2.0.0
 * @ return void
 */
function load_nf_filter_to_modify_stripe_checkout_expiration(): void	{

	add_filter( 'ninja_forms_stripe_checkout_session_create_params', __NAMESPACE__ . '\\organize_stripe_checkout_session_payload' );
}

/**
 * Shorten the Stripe checkout session expiration time for a failed payment, and get Ninja Forms metadata.
 *
 * @since 2.0.0	Intial commit.
 * @since 2.0.1	Added a check of array key and array data type, else set an empty array.
 * @since 2.0.2	Check the Ninja Forms global instance to get the metadata values for `customer_first_name` and `customer_last_name`.
 * @since 2.0.3	Refactor and rename this function to serve as an orchestrator of the included helper functions.
 *
 * @param array $session_parameters The unfiltered payload arguments passed by Ninja Forms.
 * @return array The altered payload array with modified 'expires_at' and metadata parameters.
 */
function organize_stripe_checkout_session_payload( array $session_parameters ): array {
	// 1. DEFENSIVE CHECK: Verify input integrity.
	if ( ! is_array( $session_parameters ) ) {
		return $session_parameters;
	}

	// 2. TRANSFORM: Run modifications
	$session_parameters = reduce_stripe_checkout_session_expiry_time( $session_parameters );
	$session_parameters = get_stripe_checkout_activity_type( $session_parameters );

	return $session_parameters;
}

/**
 * Reduce the Stripe checkout session expiry time for failed payment.
 *
 * 	The Unix timestamp (time()) starts the moment that a Stripe payment page opens.
 *	The minimum time allowed by Stripe to reduce the "expire_at" property is 30 min. (1800 s).
 *
 * @since 2.0.3
 *
 * @param array $session_parameters Stripe checkout session parameters
 * @return array $session_parameters Modified checkout session expiration time.
 */
function reduce_stripe_checkout_session_expiry_time( array $session_parameters ): array {
	$session_parameters['expires_at'] = time() + 1800;
	return $session_parameters;
}

/**
 * Extract and dynamically resolve the value of the Ninja Forms metadata key 'activity_type'.
 *
 * This helper function inspects whether Ninja Forms has already nested the
 * 	'activity_type' metadata key inside the Stripe 'payment_intent_data' array layer. If absent,
 * 	it seamlessly falls back to analyzing the current page context using a regex
 * 	(regular expression) equivalent match condition against the request URI path.
 *
 * @since 2.0.0
 *
 * @param array $session_parameters The active Stripe checkout session configuration parameters.
 * @return array The resolved value of the 'activity_type' metadata key to append to the top-level Stripe checkout session data object.
 *
 */
function get_stripe_checkout_activity_type( array $session_parameters ): array {
	// 1. Determine the activity type string
	// We keep your existing resilient logic
	$activity_type = '';

	if ( isset( $session_parameters['payment_intent_data']['metadata']['activity_type'] ) ) {
		$activity_type = (string) $session_parameters['payment_intent_data']['metadata']['activity_type'];
	} else {
		$current_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
		$activity_type = match ( true ) {
			str_contains( $current_uri, 'membership' ) => 'membership application',
			str_contains( $current_uri, 'awards' )     => 'annual awards banquet registration',
			str_contains( $current_uri, 'tour' )       => 'public garden tour registration',
			default                                    => 'dinner registration',
		};
	}

	// 2. Ensure metadata structure exists
	if ( ! isset( $session_parameters['payment_intent_data']['metadata'] ) ) {
		$session_parameters['payment_intent_data']['metadata'] = [];
	}

	// 3. Assign the determined string to the array key
	$session_parameters['payment_intent_data']['metadata']['activity_type'] = $activity_type;

	// 4. Return the entire modified array (maintaining the contract)
	return $session_parameters;
}
