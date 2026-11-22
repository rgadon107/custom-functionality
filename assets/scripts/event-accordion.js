/**
 * Event Accordion Toggle
 *
 * Handles expanding and collapsing the event details container on the
 * single-events.html template, updating button labels, chevron visibility,
 * and ARIA accessibility states.
 *
 * @package Custom_Functionality
 * @since   2.4.0 Initial release.
 */

document.addEventListener( 'DOMContentLoaded', function () {
	const toggleBtn = document.getElementById( 'event-read-more-btn' );
	const content = document.getElementById( 'event-accordion-content' );
	const label = document.getElementById( 'toggle-label' );
	const chevronDown = document.getElementById( 'chevron-down' );
	const chevronUp = document.getElementById( 'chevron-up' );

	if ( toggleBtn && content ) {
		toggleBtn.addEventListener( 'click', function () {
			// Toggle visibility of the wrapper.
			const isHidden = content.classList.toggle( 'event-accordion-hidden' );

			if ( isHidden ) {
				// State: Collapsed ("Read More").
				label.textContent = 'Read More';
				chevronDown.classList.remove( 'hidden-icon' );
				chevronUp.classList.add( 'hidden-icon' );
				toggleBtn.setAttribute( 'aria-expanded', 'false' );
			} else {
				// State: Expanded ("Read Less").
				label.textContent = 'Read Less';
				chevronDown.classList.add( 'hidden-icon' );
				chevronUp.classList.remove( 'hidden-icon' );
				toggleBtn.setAttribute( 'aria-expanded', 'true' );
			}
		} );
	}
} );
