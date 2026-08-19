<?php
/**
 * Generic Registrars for Custom Post-Types (CPTs) and Metadata.
 *
 * Provides unopinionated procedural worker functions to build entity labels,
 * register custom post-types, and register post metadata using configuration arrays.
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom
 * @author     Robert A Gadon
 * @license    GPL-2.0-or-later
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom;

/**
 * Generates label arrays for Custom Post-Types and Taxonomies procedurally.
 *
 * @since 2.3.0
 *
 * @param string $singular  Singular entity name (e.g., 'Auction Item').
 * @param string $plural    Plural entity name (e.g., 'Auction Items').
 * @param string $type      Optional. Entity type: 'cpt' or 'taxonomy'. Default 'cpt'.
 * @param string $domain    Optional. Text domain for i18n translation. Default 'custom-functionality'.
 * @param array  $overrides Optional. Specific label key overrides. Default empty array.
 * @return array Complete array of WordPress entity labels.
 */
function build_entity_labels( string $singular, string $plural, string $type = 'cpt', string $domain = 'custom-functionality', array $overrides = [] ): array {
	$labels = [
		'name'          => _x( $plural, 'general name', $domain ),
		'singular_name' => _x( $singular, 'singular name', $domain ),
		'search_items'  => sprintf( __( 'Search %s', $domain ), $plural ),
		'all_items'     => sprintf( __( 'All %s', $domain ), $plural ),
		'edit_item'     => sprintf( __( 'Edit %s', $domain ), $singular ),
		'update_item'   => sprintf( __( 'Update %s', $domain ), $singular ),
		'add_new_item'  => sprintf( __( 'Add New %s', $domain ), $singular ),
		'menu_name'     => __( $plural, $domain ),
	];

	if ( 'cpt' === $type ) {
		$labels['add_new']            = _x( 'Add New', strtolower( $singular ), $domain );
		$labels['new_item']           = sprintf( __( 'New %s', $domain ), $singular );
		$labels['view_item']          = sprintf( __( 'View %s', $domain ), $singular );
		$labels['not_found']          = sprintf( __( 'No %s found', $domain ), strtolower( $plural ) );
		$labels['not_found_in_trash'] = sprintf( __( 'No %s found in Trash', $domain ), strtolower( $plural ) );
	} elseif ( 'taxonomy' === $type ) {
		$labels['parent_item']       = sprintf( __( 'Parent %s', $domain ), $singular );
		$labels['parent_item_colon'] = sprintf( __( 'Parent %s:', $domain ), $singular );
		$labels['new_item_name']     = sprintf( __( 'New %s Name', $domain ), $singular );
	}

	return array_merge( $labels, $overrides );
}

/**
 * Generic worker function to register a Custom Post-Type from a configuration array.
 *
 * @since 2.3.0
 *
 * @param array $config {
 *     Configuration array containing CPT parameters.
 *
 *     @type string $post_type The post-type slug.
 *     @type array  $args      Arguments passed to register_post_type().
 * }
 * @return void
 */
function register_cpt_from_config( array $config ): void {
	if ( empty( $config['post_type'] ) || empty( $config['args'] ) ) {
		return;
	}

	register_post_type( $config['post_type'], $config['args'] );
}

/**
 * Generic worker function to register custom post metadata from a configuration array.
 *
 * @since 2.3.0
 *
 * @param array $config {
 *     Configuration array containing post metadata parameters.
 *
 *     @type string $post_type   The post-type slug.
 *     @type array  $meta_fields Array of meta fields keyed by meta_key, containing
 *                               arguments passed to register_post_meta().
 * }
 * @return void
 */
function register_meta_from_config( array $config ): void {
	if ( empty( $config['post_type'] ) || empty( $config['meta_fields'] ) ) {
		return;
	}

	foreach ( $config['meta_fields'] as $meta_key => $args ) {
		register_post_meta( $config['post_type'], $meta_key, $args );
	}
}
