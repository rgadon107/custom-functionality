/**
 * Event Accordion Toggle
 *
 * Handles expanding and collapsing event details containers across single-event
 * templates, event archives, and multi-event card Query Loops without duplicate HTML IDs.
 *
 * @package Custom_Functionality
 * @since   2.4.0 Initial release.
 */

document.addEventListener( 'DOMContentLoaded', function () {
	const toggleButtons = document.querySelectorAll( '.event-read-more-btn' );

	toggleButtons.forEach( function ( toggleBtn ) {
		const card = toggleBtn.closest( '.custom-tight-card' );
		if ( ! card ) {
			return;
		}

		const content = card.querySelector( '.event-accordion-content' );
		const label = toggleBtn.querySelector( '.toggle-label' );
		const chevronDown = toggleBtn.querySelector( '.chevron-down' );
		const chevronUp = toggleBtn.querySelector( '.chevron-up' );

		if ( ! content ) {
			return;
		}

		toggleBtn.addEventListener( 'click', function () {
			const isHidden = content.classList.toggle( 'event-accordion-hidden' );

			if ( isHidden ) {
				if ( label ) {
					label.textContent = 'Read More';
				}
				if ( chevronDown ) {
					chevronDown.classList.remove( 'hidden-icon' );
				}
				if ( chevronUp ) {
					chevronUp.classList.add( 'hidden-icon' );
				}
				toggleBtn.setAttribute( 'aria-expanded', 'false' );
			} else {
				if ( label ) {
					label.textContent = 'Read Less';
				}
				if ( chevronDown ) {
					chevronDown.classList.add( 'hidden-icon' );
				}
				if ( chevronUp ) {
					chevronUp.classList.remove( 'hidden-icon' );
				}
				toggleBtn.setAttribute( 'aria-expanded', 'true' );
			}
		} );
	} );
} );
