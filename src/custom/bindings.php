<?php

/**
 * Custom Block Bindings API Sources for Events Metadata.
 *
 * Registers custom block binding sources (such as `gcm/formatted-event-date`)
 * on the `init` hook to transform raw post metadata values into formatted,
 * human-readable strings for frontend rendering within block patterns and
 * Query Loop templates.
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage Source\Custom
 * @since      2.4.0 Initial release.
 */

declare(strict_types=1);

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom;

add_action('init', __NAMESPACE__ . '\register_event_block_bindings');
/**
 * Registers custom block binding sources for formatted event metadata.
 *
 * @return void
 *
 * @since 2.4.0 Initial release
 *
 */
function register_event_block_bindings(): void	{
	register_block_bindings_source(
		'gcm/formatted-event-date',
		[
			'label'				 => __('Formatted Event Date', 'custom-functionality'),
			'get_value_callback' => __NAMESPACE__ . '\get_formatted_event_date_binding',
			'uses_context' 		 => ['postId'],
		],
	);

	register_block_bindings_source(
		'gcm/event-meta',
		[
			'label'              => __( 'Event Metadata (Empty on Blank)', 'custom-functionality' ),
			'get_value_callback' => __NAMESPACE__ . '\get_event_meta_binding',
			'uses_context'       => [ 'postId' ],
		]
	);
}

/**
 * Retrieves and formats an event date meta value as "Tuesday, January 12, 2027".
 *
 * @param array $source_args Arguments passed from the block binding attributes.
 * @param \WP_Block $block_instance The block instance being rendered.
 * @return string|null The formatted date string, or null if empty/invalid.
 *
 * @since 2.4.0 Initial release
 *
 */
function get_formatted_event_date_binding(array $source_args, \WP_Block $block_instance): ?string	{
	$post_id = $block_instance->context['postId'] ?? get_the_ID();
	if (!$post_id) {
		return null;
	}

	$meta_key = $source_args['key'] ?? '_event_start_date';
	$raw_value = get_post_meta((int)$post_id, $meta_key, true);

	if (!is_string($raw_value) || '' === $raw_value) {
		return '';
	}

	$date = \DateTimeImmutable::createFromFormat('Y-m-d', $raw_value, wp_timezone());
	if (!$date) {
		return esc_html($raw_value);
	}

	// 'l, F j, Y' outputs: Tuesday, January 12, 2027
	return wp_date('l, F j, Y', $date->getTimestamp());
}

/**
 * Retrieves an event meta value as a sanitized string, returning '' when empty
 * so template placeholder text is cleared on the frontend.
 *
 * @param array     $source_args    Arguments passed from the block binding attributes.
 * @param \WP_Block $block_instance The block instance being rendered.
 * @return string|null The meta value string, empty string if blank, or null if no post ID/key.
 *
 * @since 2.4.0 Intial release.
 */
function get_event_meta_binding( array $source_args, \WP_Block $block_instance ): ?string {
	$post_id  = $block_instance->context['postId'] ?? get_the_ID();
	$meta_key = $source_args['key'] ?? '';

	if ( ! $post_id || '' === $meta_key ) {
		return null;
	}

	$raw_value = get_post_meta( (int) $post_id, $meta_key, true );

	if ( ! is_string( $raw_value ) || '' === trim( $raw_value ) ) {
		return '';
	}

	return esc_html( $raw_value );
}
