<?php

declare(strict_types=1);

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom;

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
