<?php

declare(strict_types=1);

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom;

/**
 * Sanitizes and validates an event date string against the YYYY-MM-DD format.
 *
 * Ensures the input is a non-empty string matching a valid calendar date
 * in 'Y-m-d' format before saving it to post metadata. Returns an empty
 * string if validation fails.
 *
 * @since 2.3.6
 *
 * @param mixed $value The raw meta value submitted for sanitization.
 * @return string The validated date string in YYYY-MM-DD format, or an empty string.
 */
function sanitize_event_date( mixed $value ): string {
	if ( ! is_string( $value ) || '' === $value ) {
		return '';
	}

	$sanitized = sanitize_text_field( $value );
	$date      = \DateTime::createFromFormat( 'Y-m-d', $sanitized );

	return ( $date && $date->format( 'Y-m-d' ) === $sanitized ) ? $sanitized : '';
}

/**
 * Authorizes REST API and editor modifications for protected event metadata keys.
 *
 * Callback for the `auth_{$object_type}_meta_{$meta_key}` filter to verify
 * whether the current user has permission to edit the specified event post.
 *
 * @since 2.3.6
 *
 * @param bool   $allowed  Whether the user can add or edit the post meta. Default false.
 * @param string $meta_key The meta key being checked.
 * @param int    $post_id  The ID of the event post being edited.
 * @return bool True if the current user can edit the post, false otherwise.
 */
function authorize_event_meta_edit( bool $allowed, string $meta_key, int $post_id ): bool {
	return current_user_can( 'edit_post', $post_id );
}

return [
	'post_type' => 'events',
	'args' => [
		'labels' => [
			'name' => __('Events', 'custom-functionality'),
			'singular_name' => __('Event', 'custom-functionality'),
			'menu_name' => __('Events', 'custom-functionality'),
			'name_admin_bar' => __('Event', 'custom-functionality'),
			'add_new' => __('Add New', 'custom-functionality'),
			'add_new_item' => __('Add New Event', 'custom-functionality'),
			'new_item' => __('New Event', 'custom-functionality'),
			'edit_item' => __('Edit Event', 'custom-functionality'),
			'view_item' => __('View Event', 'custom-functionality'),
			'all_items' => __('All Events', 'custom-functionality'),
			'search_items' => __('Search Events', 'custom-functionality'),
			'not_found' => __('No events found.', 'custom-functionality'),
			'not_found_in_trash' => __('No events found in Trash.', 'custom-functionality'),
		],
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => ['slug' => 'events'],
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-calendar-alt',
		'show_in_rest' => true, // Essential for the Block Editor and pattern injection
		'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'page-attributes'],

		// Inject the default editor template
		'template' => [
			// Inject a registered pattern from the ixion-child theme
			['core/pattern', ['slug' => 'ixion-child/event-header-pattern']],
			// Add a default paragraph block below the pattern
			['core/paragraph', ['placeholder' => 'Enter event description and details here...']],
		],
		// 'template_lock'    => 'all', // Uncomment to prevent authors from deleting/moving the top-level blocks
	],

	// Registering metadata for the CPT
	'meta_fields' => [
		[
			'meta_key' => '_event_start_date',
			'meta_type' => 'post',
			'args' => [
				'type' => 'string',
				'description' => 'Start date of the event (YYYY-MM-DD)',
				'single' => true,
				'show_in_rest' => true, // Allows access via the Block Editor/REST API
			],
		],
		[
			'meta_key' => '_event_location',
			'meta_type' => 'post',
			'args' => [
				'type' => 'string',
				'description' => 'Physical location or URL of the event',
				'single' => true,
				'show_in_rest' => true,
			],
		],
	],
];
