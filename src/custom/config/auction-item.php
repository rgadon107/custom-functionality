<?php

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom;

return [
	'post_type' => 'auction_item',
	'args'      => [
		'labels'             => build_entity_labels( 'Auction Item', 'Auction Items' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'auction-item' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-tickets-alt',
		'supports'           => [ 'title', 'excerpt', 'custom-fields', 'revisions' ],
		'show_in_rest'       => true,
		'rest_base'          => 'auction_item',
	],
	'meta_fields' => [
		'auction_item_description' => [
			'type'              => 'string',
			'description'       => 'Full item description (max 650 chars)',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => function( $value ) {
				$sanitized = sanitize_textarea_field( $value );
				return mb_substr( $sanitized, 0, 650 );
			},
		],
		'donor_first_name' => [
			'type'              => 'string',
			'description'       => 'Donor First Name',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
		],
		'donor_last_name' => [
			'type'              => 'string',
			'description'       => 'Donor Last Name',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
		],
		'auction_item_value' => [
			'type'              => 'number',
			'description'       => 'Estimated Item Value in USD',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => function( $value ) {
				$clean = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
				return round( (float) $clean, 2 );
			},
		],
		'auction_item_minimum_bid' => [
			'type'              => 'number',
			'description'       => 'Minimum Opening Bid in USD',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => function( $value ) {
				$clean = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
				return round( (float) $clean, 2 );
			},
		],
	],
];
