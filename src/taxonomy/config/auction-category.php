<?php

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy;

use function gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom\build_entity_labels;

return [
	'taxonomy'  => 'auction_category',
	'object_type' => [ 'auction_item' ],
	'args'      => [
		'hierarchical'      => true,
		'labels'            => build_entity_labels(
			'Auction Category',
			'Auction Categories',
			'taxonomy',
			'custom-functionality',
			[
				'menu_name' => __(
					'Categories',
					'custom-functionality'
				),
			]
		),
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'          => true,
		'rewrite'           => [ 'slug' => 'auction-category' ],
		'show_in_rest'      => true,
		'rest_base'         => 'auction_category',
	],
];
