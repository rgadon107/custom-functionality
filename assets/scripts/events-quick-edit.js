/**
 * Events Admin Quick Edit Handler
 *
 * Extends WordPress core's `inlineEditPost.edit` to populate the custom
 * `event_start_date` field from the row's hidden `.event-start-date-raw` span
 * and configures core's native `menu_order` input as a step-1 number field.
 *
 * Enqueued script dependencies (registered via PHP in /src/asset/handler.php):
 * - jquery
 * - inline-edit-post
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage Assets\Scripts
 * @since      2.4.1 Initial release.
 */

/* global inlineEditPost */
( function ( $ ) {
	if ( typeof inlineEditPost === 'undefined' ) {
		return;
	}

	const originalEdit = inlineEditPost.edit;

	inlineEditPost.edit = function ( id ) {
		originalEdit.apply( this, arguments );

		let postId = 0;
		if ( typeof id === 'object' ) {
			postId = parseInt( this.getId( id ), 10 );
		}

		if ( postId > 0 ) {
			const $postRow      = $( '#post-' + postId );
			const $editRow      = $( '#edit-' + postId );
			const rawStartDate  = $postRow.find( '.event-start-date-raw' ).text().trim();
			const postStatus    = $postRow.find( '.post_status' ).text().trim();
			const $statusSelect = $editRow.find( 'select[name="_status"]' );

			$editRow.find( 'input[name="event_start_date"]' ).val( rawStartDate );
			$editRow.find( 'input[name="menu_order"]' ).attr( { type: 'number', step: '1', min: '0' } );

			// Ensure 'Private' is available in the Quick Edit Status dropdown.
			if ( $statusSelect.length && 0 === $statusSelect.find( 'option[value="private"]' ).length ) {
				$statusSelect.append( $( '<option>', { value: 'private', text: 'Private' } ) );
			}

			// Optional: Ensure 'Scheduled' is always visible in the Status dropdown.
			if ( $statusSelect.length && 0 === $statusSelect.find( 'option[value="future"]' ).length ) {
				$statusSelect.append( $( '<option>', { value: 'future', text: 'Scheduled' } ) );
			}

			// Sync the selected option to the row's current status.
			if ( postStatus && $statusSelect.find( 'option[value="' + postStatus + '"]' ).length ) {
				$statusSelect.val( postStatus );
			}
		}
	};
} )( jQuery );
