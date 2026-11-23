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

use WP_Block;

add_action('init', __NAMESPACE__ . '\register_event_block_bindings');
/**
 * Registers custom block binding sources for formatted event metadata.
 *
 * @return void
 *
 * @since 2.4.0 Initial release
 *
 */
function register_event_block_bindings(): void {
	$binding_sources = [
		'gcm/formatted-event-date' => [
			'label'    => __( 'Formatted Event Date', 'custom-functionality' ),
			'callback' => 'get_formatted_event_date_binding',
		],
		'gcm/event-meta'           => [
			'label'    => __( 'Event Metadata (Empty on Blank)', 'custom-functionality' ),
			'callback' => 'get_event_meta_binding',
		],
		'gcm/event-card-field'     => [
			'label'    => __( 'Event Card Composite Field', 'custom-functionality' ),
			'callback' => 'get_event_card_composite_binding',
		],
		'gcm/event-category-badge' => [
			'label'    => __( 'Event Category Badge (Meetings & Tours)', 'custom-functionality' ),
			'callback' => 'get_event_category_badge_binding',
		],
	];

	foreach ( $binding_sources as $source_name => $config ) {
		register_block_bindings_source(
			$source_name,
			[
				'label'              => $config['label'],
				'get_value_callback' => __NAMESPACE__ . '\\' . $config['callback'],
				'uses_context'       => $config['uses_context'] ?? [ 'postId' ],
			]
		);
	}
}

/**
 * Retrieves and formats an event date meta value as "Tuesday, January 12, 2027".
 *
 * @param array $source_args Arguments passed from the block binding attributes.
 * @param WP_Block $block_instance The block instance being rendered.
 * @return string|null The formatted date string, or null if empty/invalid.
 *
 * @since 2.4.0 Initial release
 *
 */
function get_formatted_event_date_binding(array $source_args, WP_Block $block_instance): ?string	{
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
 * @param WP_Block $block_instance The block instance being rendered.
 * @return string|null The meta value string, empty string if blank, or null if no post ID/key.
 *
 * @since 2.4.0 Intial release.
 *
 */
function get_event_meta_binding( array $source_args, WP_Block $block_instance ): ?string {
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

/**
 * Builds composite HTML strings (byline, organization link, location link)
 * for single-line event card paragraphs.
 *
 * @param array     $source_args    Arguments passed from the block binding attributes.
 * @param WP_Block $block_instance The block instance being rendered.
 * @return string|null Formatted HTML string, empty string if blank, or null if invalid.
 *
 * @since 2.4.0 Intial release
 *
 */
function get_event_card_composite_binding( array $source_args, WP_Block $block_instance ): ?string {
	$post_id = $block_instance->context['postId'] ?? get_the_ID();
	$field   = $source_args['field'] ?? '';

	if ( ! $post_id || '' === $field ) {
		return null;
	}

	$id = (int) $post_id;

	switch ( $field ) {
		case 'presenter_byline':
			$name = trim( (string) get_post_meta( $id, '_event_speaker_name', true ) );
			if ( '' === $name ) {
				return '';
			}
			return '<em>' . esc_html( sprintf( 'Presented by %s', $name ) ) . '</em>';

		case 'presenter_org':
			$title = trim( (string) get_post_meta( $id, '_event_speaker_title', true ) );
			$org   = trim( (string) get_post_meta( $id, '_event_speaker_organization', true ) );
			$url   = trim( (string) get_post_meta( $id, '_event_speaker_url', true ) );

			if ( '' === $title && '' === $org ) {
				return '';
			}

			$org_html = '';
			if ( '' !== $org ) {
				$org_html = '' !== $url
					? '<a href="' . esc_url( $url ) . '"><strong>' . esc_html( $org ) . '</strong></a>'
					: sprintf( '<strong>%s</strong>', esc_html( $org ) );
			}

			if ( '' !== $title && '' !== $org_html ) {
				return esc_html( $title ) . ' ' . $org_html;
			}

			return '' !== $title ? esc_html( $title ) : $org_html;

		case 'location_card':
			$loc_name    = trim( (string) get_post_meta( $id, '_event_location_name', true ) );
			$loc_address = trim( (string) get_post_meta( $id, '_event_location_address', true ) );
			$map_url     = trim( (string) get_post_meta( $id, '_event_location_map_url', true ) );

			if ( '' === $loc_name && '' === $loc_address ) {
				return '';
			}

			$lines = array_filter(
				[
					'' !== $loc_name ? esc_html( $loc_name ) : '',
					'' !== $loc_address ? nl2br( esc_html( $loc_address ), false ) : '',
				]
			);

			$inner = implode( '<br>', $lines );

			return '' !== $map_url
				? '<a href="' . esc_url( $map_url ) . '" target="_blank" rel="noreferrer noopener">' . $inner . '</a>'
				: $inner;

		default:
			return null;
	}
}

/**
 * Returns formatted `event-category` taxonomy term name(s) only when the event
 * belongs to a designated display category (such as Club Meetings or Tours).
 *
 * Returns an empty string for self-explanatory categories (e.g., Plant Sale,
 * Awards Banquet) so no badge is rendered on those cards.
 *
 * @since 2.4.0 Initial release.
 *
 * @param array     $source_args    Arguments passed from the block binding attributes.
 * @param WP_Block $block_instance The block instance being rendered.
 * @return string|null Formatted term HTML, empty string if excluded/none, or null if invalid.
 */
function get_event_category_badge_binding( array $source_args, WP_Block $block_instance ): ?string {
	$post_id = $block_instance->context['postId'] ?? get_the_ID();
	if ( ! $post_id ) {
		return null;
	}

	// Slugs of event-category terms that should display a badge on the front end.
	$allowed_slugs = $source_args['slugs'] ?? [
		'club-meeting',
		'tour',
		'members-only-event',
		'public-event'
	];

	$terms = get_the_terms( (int) $post_id, 'event-category' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	$matched_names = [];
	foreach ( $terms as $term ) {
		if ( in_array( $term->slug, $allowed_slugs, true ) ) {
			$matched_names[] = esc_html( $term->name );
		}
	}

	if ( empty( $matched_names ) ) {
		return '';
	}

	return implode( ', ', $matched_names );
}
