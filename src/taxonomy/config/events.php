<?php
/**
 * Configuration for the `Event` category custom taxonomy.
 *
 * Defines both the registration parameters for register_taxonomy_from_config()
 * and the default terms payload for seed_taxonomy_terms_from_config().
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy\Config
 * @author     Robert A Gadon
 * @license    GPL-2.0-or-later
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy\Config;

/**
 * Returns the complete configuration array for the 'event-category' taxonomy.
 *
 * @since 2.4.0
 *
 * @return array{
 *     taxonomy: string,
 *     object_type: array<int, string>,
 *     args: array<string, mixed>,
 *     terms: array<string, array{name: string, description: string}>
 * }
 */
function get_event_category_taxonomy_config(): array {
	$labels = [
		'name'                       => _x( 'Event Categories', 'taxonomy general name', 'custom-functionality' ),
		'singular_name'              => _x( 'Event Category', 'taxonomy singular name', 'custom-functionality' ),
		'search_items'               => __( 'Search Event Categories', 'custom-functionality' ),
		'all_items'                  => __( 'All Event Categories', 'custom-functionality' ),
		'parent_item'                => __( 'Parent Event Category', 'custom-functionality' ),
		'parent_item_colon'          => __( 'Parent Event Category:', 'custom-functionality' ),
		'edit_item'                  => __( 'Edit Event Category', 'custom-functionality' ),
		'update_item'                => __( 'Update Event Category', 'custom-functionality' ),
		'add_new_item'               => __( 'Add New Event Category', 'custom-functionality' ),
		'new_item_name'              => __( 'New Event Category Name', 'custom-functionality' ),
		'menu_name'                  => __( 'Categories', 'custom-functionality' ),
		'not_found'                  => __( 'No event categories found.', 'custom-functionality' ),
		'no_terms'                   => __( 'No event categories', 'custom-functionality' ),
		'items_list_navigation'      => __( 'Event categories list navigation', 'custom-functionality' ),
		'items_list'                 => __( 'Event categories list', 'custom-functionality' ),
		'back_to_items'              => __( '&larr; Go to Event Categories', 'custom-functionality' ),
	];

	return [
		'taxonomy'    => 'event-category',
		'object_type' => [ 'events' ],
		'args'        => [
			'labels'            => $labels,
			'description'       => __( 'Categorizes Garden Club of Minneapolis events by format and audience.', 'custom-functionality' ),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => [
				'slug'         => 'events/category',
				'with_front'   => false,
				'hierarchical' => true,
			],
		],
		'terms'       => [
			'club-meeting'       => [
				'name'        => __( 'Club Meeting', 'custom-functionality' ),
				'description' => __( 'Regular monthly membership meetings and educational programs.', 'custom-functionality' ),
			],
			'plant-sale'         => [
				'name'        => __( 'Plant Sale', 'custom-functionality' ),
				'description' => __( 'Annual spring plant sales and club fundraising markets.', 'custom-functionality' ),
			],
			'tour'               => [
				'name'        => __( 'Tour', 'custom-functionality' ),
				'description' => __( 'Tours of gardens, plant nurseries, or arboreta for educational, social, or aesthetic purposes.', 'custom-functionality' ),
			],
			'fff-show'               => [
				'name'        => __( 'Flower, Food & Foto Show', 'custom-functionality' ),
				'description' => __( 'A public exhibition and contest to publically display the finest plants, floral designs and photography.', 'custom-functionality' ),
			],
			'awards-banquet'     => [
				'name'        => __( 'Awards Banquet', 'custom-functionality' ),
				'description' => __( 'Annual awards banquets and celebratory club gatherings.', 'custom-functionality' ),
			],
			'members-only-event' => [
				'name'        => __( 'Members-Only Event', 'custom-functionality' ),
				'description' => __( 'An event restricted exclusively to active Garden Club of Minneapolis members and their guests.', 'custom-functionality' ),
			],
			'public-event' => [
				'name'        => __( 'Public Event', 'custom-functionality' ),
				'description' => __( 'A club-sponsored event that is open to the general public.', 'custom-functionality' ),
			],
		],
	];
}
