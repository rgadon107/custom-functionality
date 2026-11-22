<?php

/**
 * Custom callbacks hooked to WordPress actions or filters.
 *
 * @package     gardenClubOfMpls\CustomFunctionalityPlugin\Source
 * @since       1.0.0
 * @author      Robert Gadon
 * @link        https://github.com/rgadon107/custom-functionality
 * @license     GPL-2.0+
 */

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source;

use function gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom\register_cpt_from_config;
use function gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom\register_meta_from_config;
use function gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom\register_cpt_block_templates;
use gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy as Taxonomy;
use gardenClubOfMpls\CustomFunctionalityPlugin\Source\Taxonomy\Config as TaxonomyConfig;
use WP_HTML_Tag_Processor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load worker registrars so function definitions exist in memory immediately.
require_once __DIR__ . '/custom/registrar.php';
require_once __DIR__ . '/taxonomy/registrar.php';

add_filter( 'post_password_expires', __NAMESPACE__ . '\modify_cookie_expiration' );
/**
 * Make the protected-page password cookie a session cookie.
 * * Require visitors to reenter the password to access member-restricted content.
 *
 * @since 1.1.0
 * @since 2.2.2 Changed cookie expiration time from 0 to 1 hour from when browser session starts.
 *
 * @param 	int $expires Expiration timestamp passed to set cookie.
 * @return 	int Create a session cookie that expires in 1 hour from when a browser session begins.
 */
function modify_cookie_expiration( int $expires ): int {
	return time() + HOUR_IN_SECONDS;
}

add_filter( 'http_request_args', __NAMESPACE__. '\filter_http_request_timeout', 10, 2 );
/**
 * Increase the default HTTP request timeout to handle latent responses from
 * Constant Contact during household membership applications.
 *
 * @since 1.0.0
 *
 * @param array<string, mixed> $http_request_args   The parsed arguments for the HTTP request.
 * @param string               $url                 The request URL.
 * @return array<string, mixed> 					The modified HTTP request arguments.
 */
function filter_http_request_timeout( array $http_request_args, string $url ): array
{

	if ( str_contains( $url, 'constantcontact.com' ) ) {

		// Increase the http request timeout from 5 ( default ) to 20 seconds.
		$http_request_args['timeout'] = 20;
	}

	return $http_request_args;
}

add_action( 'template_redirect', __NAMESPACE__. '\exclude_page_cache' );
/**
 * Exclude `The Garden Spray` newsletter archive page from server and browser caching.
 *
 * @since 2.2.1 Initial release.
 * @since 2.3.1 Refactored function match any page slug starting with 'gardenspray'.
 *
 * @return void
 */
function exclude_page_cache(): void {

	if ( ! is_page() ) {
		return;
	}

	$page = get_queried_object();

	// Matches 'gardenspray', 'gardenspray-archive', or any slug starting with 'gardenspray'
	if ( isset( $page->post_name ) && str_starts_with( $page->post_name, 'gardenspray' ) ) {

		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}

		nocache_headers();
	}
}

add_action( 'init', __NAMESPACE__ . '\initialize_custom_taxonomies', 6 );
/**
 * Custom Taxonomy Initialization Hooks.
 *
 * Handles the loading of taxonomy configuration arrays and initiates taxonomy
 * 	registrations on the WordPress init hook.
 *
 * @since 2.3.0 Initial release.
 *
 * @return void
 */
function initialize_custom_taxonomies(): void {
	$tax_configs = [
		__DIR__ . '/taxonomy/config/auction-category.php',
		// Future taxonomy configs go here.
	];

	foreach ( $tax_configs as $file_path ) {
		if ( file_exists( $file_path ) ) {
			$config = require $file_path;

			Taxonomy\register_taxonomy_from_config( $config );
		}
	}
}

add_action( 'init', __NAMESPACE__ . '\initialize_custom_post_types', 7 );
/**
 * Custom Post-Type Initialization Hooks.
 *
 * Handles the loading of CPT configuration arrays and initiates post-type
 *  and metadata registrations on the WordPress init hook.
 *
 * @since 2.3.0 Initial release.
 *
 * @return void
 */
function initialize_custom_post_types(): void {
	// Array of configuration file paths to load.
	$cpt_configs = [
		__DIR__ . '/custom/config/auction-item.php',
		__DIR__ . '/custom/config/events.php',
		// Future CPT configs go here: __DIR__ . '/custom/config/members.php',
	];

	foreach ( $cpt_configs as $file_path ) {
		if ( file_exists( $file_path ) ) {
			$config = require $file_path;

			register_cpt_from_config( $config );
			register_meta_from_config( $config );
		}
	}
}

add_action( 'init', __NAMESPACE__ . '\register_plugin_taxonomies', 10 );
add_action( 'init', __NAMESPACE__ . '\seed_plugin_taxonomy_terms', 20 );

/**
 * Registers all custom taxonomies defined in plugin configuration files.
 *
 * @since 2.4.0 Initial release.
 *
 * @return void
 */
function register_plugin_taxonomies(): void {
	$taxonomy_configs = [
		TaxonomyConfig\get_event_category_taxonomy_config(),
	];

	foreach ( $taxonomy_configs as $config ) {
		Taxonomy\register_taxonomy_from_config( $config );
	}
}

/**
 * Seeds default terms for all custom taxonomies that define a 'terms' payload.
 *
 * @since 2.4.0 Intial release.
 *
 * @return void
 */
function seed_plugin_taxonomy_terms(): void {
	$taxonomy_configs = [
		TaxonomyConfig\get_event_category_taxonomy_config(),
		// TaxonomyConfig\get_member_role_taxonomy_config(),
		// TaxonomyConfig\get_photo_category_taxonomy_config(),
		// TaxonomyConfig\get_newsletter_issue_taxonomy_config(),
	];

	foreach ( $taxonomy_configs as $config ) {
		Taxonomy\seed_taxonomy_terms_from_config( $config );
	}
}

/**
 * Registers custom post type block templates with the Site Editor on the `init` hook.
 *
 * @since 2.4.0 Initial release.
 * @see   \gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom\register_cpt_block_templates()
 */
add_action( 'init', register_cpt_block_templates(...) );

add_filter( 'render_block', __NAMESPACE__ . '\inject_event_card_anchor', 10, 2 );
add_filter( 'render_block', __NAMESPACE__ . '\filter_scheduled_event_blocks', 10, 2 );
/**
 * Injects a dynamic HTML anchor ID onto the .custom-tight-card Group block
 * in single-events.html using '_event_anchor_id' or falling back to the post slug.
 *
 * @param string $block_content The rendered HTML of the block.
 * @param array  $block         The full block array, including name and attributes.
 * @return string Modified block HTML with the injected id attribute.
 *
 * @since 2.4.0 Initial release.
 *
 */
function inject_event_card_anchor( string $block_content, array $block ): string {
	if (
		( $block['blockName'] ?? '' ) !== 'core/group' ||
		empty( $block['attrs']['className'] ) ||
		! str_contains( $block['attrs']['className'], 'custom-tight-card' )
	) {
		return $block_content;
	}

	if ( ! \is_singular( 'events' ) ) {
		return $block_content;
	}

	$post_id = \get_the_ID();
	if ( ! $post_id ) {
		return $block_content;
	}

	$custom_anchor = \get_post_meta( $post_id, '_event_anchor_id', true );
	$anchor_id     = is_string( $custom_anchor ) && '' !== trim( $custom_anchor )
		? \sanitize_title( $custom_anchor )
		: \get_post_field( 'post_name', $post_id );

	if ( empty( $anchor_id ) ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag() ) {
		$tags->set_attribute( 'id', $anchor_id );
		$block_content = $tags->get_updated_html();
	}

	return $block_content;
}

/**
 * Controls scheduled visibility for registration blocks in single-events.html
 * and renders styled before/after status messages when outside the active window.
 *
 * @since 2.4.0
 *
 * @param string $block_content The rendered block HTML.
 * @param array  $block         The parsed block array.
 * @return string Modified block HTML or custom status message markup.
 */
function filter_scheduled_event_blocks( string $block_content, array $block ): string {
	if ( ! \is_singular( 'events' ) || empty( $block['attrs']['className'] ) ) {
		return $block_content;
	}

	$post_id = \get_the_ID();
	if ( ! $post_id ) {
		return $block_content;
	}

	$class_name = $block['attrs']['className'];
	$is_notice  = str_contains( $class_name, 'dinner-preregistration-notice' );
	$is_button  = str_contains( $class_name, 'register-button' );

	if ( ! $is_notice && ! $is_button ) {
		return $block_content;
	}

	$tz        = \wp_timezone();
	$now       = new \DateTimeImmutable( 'now', $tz );
	$start_raw = trim( (string) \get_post_meta( $post_id, '_event_registration_start_date', true ) );
	$stop_raw  = trim( (string) \get_post_meta( $post_id, '_event_registration_stop_date', true ) );

	// 1. Check if current time is BEFORE registration opens
	if ( '' !== $start_raw ) {
		$start_normalized = substr( str_replace( 'T', ' ', $start_raw ), 0, 16 );
		$start_dt         = 10 === strlen( $start_normalized )
			? \DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', $start_normalized . ' 00:00:00', $tz )
			: \DateTimeImmutable::createFromFormat( 'Y-m-d H:i', $start_normalized, $tz );

		if ( $start_dt && $now < $start_dt ) {
			// Suppress the preregistration notice before registration opens
			if ( $is_notice ) {
				return '';
			}

			$custom_before = trim( (string) \get_post_meta( $post_id, '_event_registration_before_message', true ) );
			$is_midnight = '00:00' === $start_dt->format( 'H:i' );
			$formatted   = $is_midnight
				? \wp_date( 'l, F j, Y', $start_dt->getTimestamp() )
				: \wp_date( 'l, F j, Y \a\t g:i A', $start_dt->getTimestamp() );

			$message = '' !== $custom_before
				? $custom_before
				: sprintf(
				/* translators: %s: Formatted registration opening date (and optional time) */
					\__( 'Registration opens %s.', 'custom-functionality' ),
					$formatted
				);

			return sprintf(
				'<p class="event-registration-status is-upcoming wp-block-paragraph">%s</p>',
				\wp_kses_post( $message )
			);
		}
	}

	// 2. Check if current time is AFTER registration closes
	if ( '' !== $stop_raw ) {
		$stop_normalized = substr( str_replace( 'T', ' ', $stop_raw ), 0, 16 );
		$stop_dt         = 10 === strlen( $stop_normalized )
			? \DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', $stop_normalized . ' 23:59:59', $tz )
			: \DateTimeImmutable::createFromFormat( 'Y-m-d H:i', $stop_normalized, $tz );

		if ( $stop_dt && $now > $stop_dt ) {
			// Suppress the preregistration notice after registration closes
			if ( $is_notice ) {
				return '';
			}

			$custom_after = trim( (string) \get_post_meta( $post_id, '_event_registration_after_message', true ) );
			$message      = '' !== $custom_after
				? $custom_after
				: \__( 'Registration for this event has closed.', 'custom-functionality' );

			return sprintf(
				'<p class="event-registration-status is-closed wp-block-paragraph">%s</p>',
				\wp_kses_post( $message )
			);
		}
	}

	return $block_content;
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\sort_events_front_end_archive' );
/**
 * Orders the front-end 'events' post type archive chronologically by the `_event_start_date` meta_field.
 *
 * @since 2.4.0 Initial release
 *
 * @param \WP_Query $query The WP_Query instance (passed by reference).
 * @return void
 */
function sort_events_front_end_archive( \WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'events' ) || $query->is_tax( 'event-category' ) ) {
		$query->set( 'meta_key', '_event_start_date' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'ASC' );
	}
}

add_filter( 'render_block_core/post-excerpt', __NAMESPACE__ . '\render_full_event_description_in_loop', 10, 3 );
/**
 * Replaces the core/post-excerpt output with the full formatted event post_content
 * inside .event-description blocks so secondary sessions and full copy render inside
 * the collapsible accordion without triggering Gutenberg's editor post-content error.
 *
 * @since 2.4.0 Initial release.
 *
 * @param string    $block_content The rendered block HTML.
 * @param array     $block         The full block array.
 * @param \WP_Block $instance      The block instance with postId context.
 * @return string
 */
function render_full_event_description_in_loop( string $block_content, array $block, \WP_Block $instance ): string {
	if ( empty( $block['attrs']['className'] ) || ! str_contains( $block['attrs']['className'], 'event-description' ) ) {
		return $block_content;
	}

	$post_id = $instance->context['postId'] ?? get_the_ID();
	if ( ! $post_id ) {
		return $block_content;
	}

	$raw_content = get_post_field( 'post_content', (int) $post_id );
	if ( '' === trim( (string) $raw_content ) ) {
		return '';
	}

	$rendered_content = apply_filters( 'the_content', $raw_content );

	return sprintf(
		'<div class="entry-content event-description wp-block-post-content has-brand-text-main-color has-text-color">%s</div>',
		$rendered_content
	);
}
