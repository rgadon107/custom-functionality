<?php
/**
 * Generic Registrars for Custom Taxonomies.
 *
 * Provides unopinionated procedural worker functions to register custom
 * taxonomies using configuration arrays.
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy
 * @author     Robert A Gadon
 * @license    GPL-2.0-or-later
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy;

/**
 * Generic worker function to register a Custom Taxonomy from a configuration array.
 *
 * @since 2.3.0
 *
 * @param array $config {
 *     Configuration array containing taxonomy parameters.
 *
 *     @type string       $taxonomy    The taxonomy slug.
 *     @type array|string $object_type Object type or array of object types (e.g., post types)
 *                                     the taxonomy is bound to.
 *     @type array        $args        Arguments passed to register_taxonomy().
 * }
 * @return void
 */
function register_taxonomy_from_config( array $config ): void {
	if ( empty( $config['taxonomy'] ) || empty( $config['object_type'] ) || empty( $config['args'] ) ) {
		return;
	}

	register_taxonomy( $config['taxonomy'], $config['object_type'], $config['args'] );
}
