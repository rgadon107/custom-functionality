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

add_filter( 'ninja_forms_field_get_value', __NAMESPACE__ . '\\convert_nf_checkbox_display', 10, 2 );
/**
 * Converts checkbox binary values (1/0) to readable text strings (Yes/No).
 * * Hooked into the 'ninja_forms_field_get_value' filter via Ninja Forms field processing.
 *
 * @since 1.0.0 Initial version.
 * @since 1.2.0 Added support for listcheckbox array conversion.
 * @since 1.5.0 Added conditional check for specific Form IDs.
 * @since 1.7.0 Switched to PHP 8.0 match expression for cleaner logic.
 *
 * @param 	mixed  $value       The raw field value from the database.
 * @param 	object $field_model The Ninja Forms field model object.
 * @return 	string|mixed       	The formatted 'yes'/'no' string, comma-separated list, or original value.
 *
 */
function convert_nf_checkbox_display( $value, $field_model ) {

	$target_form_ids = [ 1006, 1008 ];
	$current_form_id = $field_model->get_setting( 'parent_id' );

	if ( ! in_array( (int) $current_form_id, $target_form_ids, true ) ) {
		return $value;
	}

	$field_type = $field_model->get_setting( 'type' );

	// Handle Single Checkboxes
	if ( $field_type === 'checkbox' ) {
		/**
		 * match() is strict, so we ensure $value is handled correctly.
		 * It returns the value directly to the caller.
		 */
		return match ( $value ) {
			1, '1'  => 'yes',
			0, '0'  => 'no',
			default => $value,
		};
	}

	// Handle Checkbox Lists
	if ( $field_type === 'listcheckbox' && is_array( $value ) ) {
		return implode( ', ', array_filter( $value ) );
	}

	return $value;
}
