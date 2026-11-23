<?php
/**
 * Custom Admin Columns, Sorting, Filtering, and Quick Edit for the 'events' Custom Post Type.
 *
 * @package gardenClubOfMpls\CustomFunctionalityPlugin\Source\Admin
 */

declare( strict_types = 1 );

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Admin;

use WP_Post;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'manage_events_posts_columns', __NAMESPACE__ . '\custom_events_admin_columns' );
/**
 * Register and order the columns for the 'events' admin list table.
 *
 * @since 2.4.0 Initial release
 *
 * @param array<string, string> $columns Existing list table columns.
 * @return array<string, string> Modified list table columns.
 */
function custom_events_admin_columns( array $columns ): array {
	return array(
		// 'cb' is the Checkbox column provided by WordPress core for bulk actions.
		'cb'               => $columns['cb'],
		'title'            => __( 'Event Title', 'custom-functionality' ),
		'event_start_date' => __( 'Start Date', 'custom-functionality' ),
		'event_category'   => __( 'Event Categories', 'custom-functionality' ),
		'menu_order'       => __( 'Page Order', 'custom-functionality' ),
		'date'             => $columns['date'],
	);
}

add_action( 'manage_events_posts_custom_column', __NAMESPACE__ . '\custom_events_admin_column_data', 10, 2 );
/**
 * Populate custom column values for each row in the 'events' admin table.
 *
 * Outputs a hidden span containing the raw YYYY-MM-DD '_event_start_date' value
 * so the Quick Edit inline script can populate the date input cleanly.
 *
 * @since 2.4.0 Initial release
 *
 * @param string $column  The name of the column to display.
 * @param int    $post_id The current post ID.
 * @return void
 */
function custom_events_admin_column_data( string $column, int $post_id ): void {
	switch ( $column ) {

		case 'event_start_date':
			$start_date = get_post_meta( $post_id, '_event_start_date', true );
			if ( ! empty( $start_date ) && is_string( $start_date ) ) {
				echo esc_html( date_i18n( get_option( 'date_format' ), (int) strtotime( $start_date ) ) );
				echo '<span class="hidden event-start-date-raw">' . esc_html( $start_date ) . '</span>';
			} else {
				echo '—';
				echo '<span class="hidden event-start-date-raw"></span>';
			}
			break;

		case 'event_category':
			$terms = get_the_terms( $post_id, 'event-category' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$term_links = array();
				foreach ( $terms as $term ) {
					$url = add_query_arg(
						array(
							'post_type'      => 'events',
							'event-category' => $term->slug,
						),
						'edit.php'
					);

					$term_links[] = '<a href="' . esc_url( $url ) . '">' . esc_html( $term->name ) . '</a>';
				}
				echo implode( ', ', $term_links );
			} else {
				echo '—';
			}
			break;

		case 'menu_order':
			$post = get_post( $post_id );
			if ( $post instanceof WP_Post ) {
				echo esc_html( (string) $post->menu_order );
			}
			break;
	}
}

add_filter( 'manage_edit-events_sortable_columns', __NAMESPACE__ . '\custom_events_sortable_columns' );
/**
 * Register sortable columns for the 'events' admin list table.
 *
 * @since 2.4.0 Initial release
 *
 * @param array<string, string> $columns Existing sortable columns.
 * @return array<string, string> Modified sortable columns.
 */
function custom_events_sortable_columns( array $columns ): array {
	$columns['event_start_date'] = 'event_start_date';
	$columns['menu_order']       = 'menu_order';
	return $columns;
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\custom_events_admin_column_orderby' );
/**
 * Define default and custom sorting behavior for the 'events' admin table.
 *
 * Uses a named meta_query with an OR relation (EXISTS / NOT EXISTS) so events
 * missing a '_event_start_date' meta value remain visible in the list table.
 *
 * @since 2.4.0 Initial release
 *
 * @param WP_Query $query The current WP_Query instance.
 * @return void
 */
function custom_events_admin_column_orderby( WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'events' !== $query->get( 'post_type' ) ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	// Apply meta_query sorting on initial load (empty orderby) or when sorting by Start Date.
	if ( empty( $orderby ) || 'event_start_date' === $orderby ) {
		$meta_query = array(
			'relation'                => 'OR',
			'event_start_date_clause' => array(
				'key'     => '_event_start_date',
				'compare' => 'EXISTS',
			),
			'missing_start_date'      => array(
				'key'     => '_event_start_date',
				'compare' => 'NOT EXISTS',
			),
		);

		$query->set( 'meta_query', $meta_query );
		$query->set( 'orderby', 'event_start_date_clause' );

		// Default to ascending chronological order on initial load.
		if ( empty( $orderby ) ) {
			$query->set( 'order', 'ASC' );
		}
	}
}

add_action( 'restrict_manage_posts', __NAMESPACE__ . '\custom_events_taxonomy_filter' );
/**
 * Render a taxonomy dropdown filter above the 'events' admin list table.
 *
 * WordPress natively filters the list table when a query variable matching the
 * taxonomy slug ('event-category') is submitted via GET on edit.php.
 *
 * @since 2.4.0 Initial release
 *
 * @param string $post_type The current post type slug.
 * @return void
 */
function custom_events_taxonomy_filter( string $post_type ): void {
	if ( 'events' !== $post_type ) {
		return;
	}

	$taxonomy_slug = 'event-category';
	$taxonomy      = get_taxonomy( $taxonomy_slug );

	if ( ! $taxonomy ) {
		return;
	}

	// Sanitize the currently selected slug from the URL query string, if present.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin table filter.
	$selected = isset( $_GET[ $taxonomy_slug ] ) ? sanitize_text_field( wp_unslash( $_GET[ $taxonomy_slug ] ) ) : '';

	wp_dropdown_categories(
		array(
			'show_option_all' => $taxonomy->labels->all_items,
			'taxonomy'        => $taxonomy_slug,
			'name'            => $taxonomy_slug,
			'orderby'         => 'name',
			'selected'        => $selected,
			'hierarchical'    => true,
			'show_count'      => true,
			'hide_empty'      => false,
			'value_field'     => 'slug',
		)
	);
}

add_action( 'quick_edit_custom_box', __NAMESPACE__ . '\custom_events_quick_edit_fields', 10, 2 );
/**
 * Render custom fields in the Quick Edit panel for 'events'.
 *
 * Fires once per custom column registered on the list table.
 *
 * @since 2.4.0 Initial release
 *
 * @param string $column_name The name of the column being rendered.
 * @param string $post_type   The current post type slug.
 * @return void
 */
function custom_events_quick_edit_fields( string $column_name, string $post_type ): void {
	if ( 'events' !== $post_type ) {
		return;
	}

	if ( 'event_start_date' === $column_name ) {
		wp_nonce_field( 'gcm_events_quick_edit_nonce', 'gcm_events_quick_edit_nonce_field' );
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php esc_html_e( 'Start Date', 'custom-functionality' ); ?></span>
					<span class="input-text-wrap">
						<input type="date" name="event_start_date" value="" />
					</span>
				</label>
			</div>
		</fieldset>
		<?php
	}

	if ( 'menu_order' === $column_name ) {
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php esc_html_e( 'Page Order', 'custom-functionality' ); ?></span>
					<span class="input-text-wrap">
						<input type="number" name="menu_order" value="0" step="1" />
					</span>
				</label>
			</div>
		</fieldset>
		<?php
	}
}

add_action( 'admin_footer-edit.php', __NAMESPACE__ . '\custom_events_quick_edit_js' );
/**
 * Populate Quick Edit fields with existing row data when 'Quick Edit' is clicked.
 *
 * @since 2.4.0 Initial release
 *
 * @return void
 */
function custom_events_quick_edit_js(): void {
	global $typenow;

	if ( 'events' !== $typenow ) {
		return;
	}
	?>
	<script>
		( function( $ ) {
			if ( typeof inlineEditPost === 'undefined' ) {
				return;
			}

			const originalEdit = inlineEditPost.edit;

			inlineEditPost.edit = function( id ) {
				originalEdit.apply( this, arguments );

				let postId = 0;
				if ( typeof id === 'object' ) {
					postId = parseInt( this.getId( id ), 10 );
				}

				if ( postId > 0 ) {
					const $postRow = $( '#post-' + postId );
					const $editRow = $( '#edit-' + postId );

					const rawStartDate = $postRow.find( '.event-start-date-raw' ).text().trim();
					const menuOrder    = $postRow.find( '.menu_order' ).text().trim();

					$editRow.find( 'input[name="event_start_date"]' ).val( rawStartDate );
					$editRow.find( 'input[name="menu_order"]' ).val( menuOrder !== '' ? menuOrder : 0 );
				}
			};
		} )( jQuery );
	</script>
	<?php
}

add_action( 'save_post_events', __NAMESPACE__ . '\custom_events_save_quick_edit' );
/**
 * Save Quick Edit values for '_event_start_date' and 'menu_order'.
 *
 * @since 2.4.0 Initial release
 *
 * @param int $post_id The ID of the post being saved.
 * @return void
 */
function custom_events_save_quick_edit( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['gcm_events_quick_edit_nonce_field'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['gcm_events_quick_edit_nonce_field'] ) );
	if ( ! wp_verify_nonce( $nonce, 'gcm_events_quick_edit_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Update or delete '_event_start_date' post meta.
	if ( isset( $_POST['event_start_date'] ) ) {
		$raw_date = sanitize_text_field( wp_unslash( $_POST['event_start_date'] ) );

		if ( '' === $raw_date ) {
			delete_post_meta( $post_id, '_event_start_date' );
		} elseif ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw_date ) ) {
			update_post_meta( $post_id, '_event_start_date', $raw_date );
		}
	}

	// Update native 'menu_order' on the post object (unhooking first to prevent recursion).
	if ( isset( $_POST['menu_order'] ) ) {
		$menu_order = intval( wp_unslash( $_POST['menu_order'] ) );

		remove_action( 'save_post_events', __NAMESPACE__ . '\custom_events_save_quick_edit' );

		wp_update_post(
			array(
				'ID'         => $post_id,
				'menu_order' => $menu_order,
			)
		);

		add_action( 'save_post_events', __NAMESPACE__ . '\custom_events_save_quick_edit' );
	}
}
