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
 * @since      2.4.2 Removed custom `_status` option injection so core's native `keep_private` checkbox handles Private posts cleanly.
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
			const $postRow     = $( '#post-' + postId );
			const $editRow     = $( '#edit-' + postId );
			const rawStartDate = $postRow.find( '.event-start-date-raw' ).text().trim();

			$editRow.find( 'input[name="event_start_date"]' ).val( rawStartDate );
			$editRow.find( 'input[name="menu_order"]' ).attr( { type: 'number', step: '1', min: '0' } );
		}
	};
} )( jQuery );
