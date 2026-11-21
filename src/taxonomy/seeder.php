<?php
/**
 * Generic Term Seeder for Custom Taxonomies.
 *
 * Provides an unopinionated procedural worker function to seed default taxonomy
 * terms from a configuration array using content-hash gating.
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy
 * @author     Robert A Gadon
 * @license    GPL-2.0-or-later
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy;

/**
 * Seeds default terms for a registered custom taxonomy from a configuration array.
 *
 * Uses an MD5 hash of the terms payload stored in wp_options so seeding runs
 * once per environment and only re-runs if the configured terms change.
 *
 * @since 2.4.0 Initial release.
 *
 * @param array $config {
 *     Configuration array containing taxonomy slug and default terms.
 *
 *     @type string $taxonomy The registered taxonomy slug.
 *     @type array  $terms    Associative array keyed by term slug, each containing
 *                            'name' (string) and optional 'description' (string).
 * }
 * @return void
 */
function seed_taxonomy_terms_from_config( array $config ): void {
	if ( empty( $config['taxonomy'] ) || empty( $config['terms'] ) || ! is_array( $config['terms'] ) ) {
		return;
	}

	$taxonomy = $config['taxonomy'];

	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}

	$option_key  = 'gcm_seeded_terms_hash_' . sanitize_key( $taxonomy );
	$target_hash = md5( (string) wp_json_encode( $config['terms'] ) );

	if ( get_option( $option_key ) === $target_hash ) {
		return;
	}

	foreach ( $config['terms'] as $term_slug => $term_data ) {
		if ( empty( $term_data['name'] ) ) {
			continue;
		}

		if ( ! term_exists( $term_slug, $taxonomy ) ) {
			wp_insert_term(
				$term_data['name'],
				$taxonomy,
				[
					'slug'        => $term_slug,
					'description' => $term_data['description'] ?? '',
				]
			);
		}
	}

	update_option( $option_key, $target_hash, false );
}
