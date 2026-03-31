<?php

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

/**
 * Shortcode: [expire]
 *
 * Shortcode to display content within a scheduled date and time window.
 *
 * Provides a "Pending -> Active -> Expired" lifecycle for wrapped content.
 * Supported attributes within the $user_defined_attributes array:
 *
 * - start_date_and_time (string) The date/time content becomes visible. Default '1970-01-01 00:00'.
 * - stop_date_and_time  (string) The date/time content is hidden. Default '2030-01-01 00:00'.
 * - pre_start_message   (string) Text shown before the start time. Default: empty.
 * - message             (string) Text shown after the stop time. Default: empty.
 *
 * @since 1.0.0
 *
 * @param array|string $user_defined_attributes  Optional. Array of shortcode attributes.
 *
 * @type string $start_date_and_time Scheduled start.
 * @type string $stop_date_and_time  Scheduled stop.
 * @type string $pre_start_message   Notice for pending state.
 * @type string $message             Notice for expired state.
 *
 * @param string|null  $content Optional. The content wrapped by the shortcode.
 *
 * @return string The filtered content if within the time window, or a notice/empty string if not.
 */
add_shortcode( 'expire', function( array|string $user_defined_attributes, ?string $content = null ): string {

	// Load the shortcode styles only on posts and pages where the shortcode is used.
	wp_enqueue_style( 'event-registration-notice-styles' );

	$default_settings = array(
		'start_date_and_time' => '1970-01-01 00:00',
		'stop_date_and_time'  => '2050-01-01 00:00',
		'pre_start_message'   => '', // Message shown BEFORE start
		'message'             => '', // Message shown AFTER stop
	);

	$final_settings = shortcode_atts( $default_settings, $user_defined_attributes );

	$start_timestamp   = strtotime( $final_settings['start_date_and_time'] );
	$stop_timestamp    = strtotime( $final_settings['stop_date_and_time'] );
	$current_timestamp = current_time( 'timestamp' );

	// STAGE 1: Before the shortcode start time, the 'pre_start_message' displays.
	if ( $current_timestamp < $start_timestamp ) {
		if ( ! empty( $final_settings['pre_start_message'] ) ) {
			return sprintf(
				'<div class="message-before-registration-start">%s</div>',
				esc_html( $final_settings['pre_start_message'] )
			);
		}
	}

	// STAGE 2: Shortcode is active. Wrapped content is displayed.
	if ( $current_timestamp >= $start_timestamp && $current_timestamp < $stop_timestamp ) {
		return do_shortcode( $content );
	}

	// STAGE 3: After shortcode stop time, the 'message' displays.
	if ( $current_timestamp >= $stop_timestamp ) {
		if ( ! empty( $final_settings['message'] ) ) {
			return sprintf(
				'<div class="message-after-registration-stop">%s</div>',
				esc_html( $final_settings['message'] )
			);
		}
		return '';
	}

	return '';
});
