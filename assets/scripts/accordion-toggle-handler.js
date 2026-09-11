/**
 * Ninja Forms Accordion Toggle Handler
 *
 * Handles collapsible content sections within Ninja Forms, including
 * multi-part form page transitions. Toggles visibility for fields marked
 * with `.accordion-target` when a `.accordion-toggle` header is clicked.
 *
 * @package     WordPress
 * @subpackage  NinjaForms
 * @author      Your Name
 * @license     GPL-2.0-or-later
 * @link        https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/
 */

document.addEventListener('DOMContentLoaded', function() {

	// Helper function to toggle accordion target fields
	function toggleAccordion(toggleHeader) {
		toggleHeader.classList.toggle('is-open');

		// Find and toggle visibility of all adjacent elements marked with 'accordion-target'
		let nextElem = toggleHeader.nextElementSibling;
		while (nextElem && nextElem.classList.contains('accordion-target')) {
			const isHidden = nextElem.style.display === 'none' || getComputedStyle(nextElem).display === 'none';
			nextElem.style.display = isHidden ? 'block' : 'none';
			nextElem = nextElem.nextElementSibling;
		}
	}

	// 1. Event Delegation: Listens for clicks anywhere on the page, even if fields re-render
	document.body.addEventListener('click', function(e) {
		const toggleHeader = e.target.closest('.accordion-toggle');
		if (toggleHeader) {
			e.preventDefault();
			toggleAccordion(toggleHeader);
		}
	});

	// 2. Ninja Forms Native Event Listener: Runs every time a step changes or form re-renders
	if (typeof Marionette !== 'undefined' && typeof nfRadio !== 'undefined') {
		nfRadio.channel('form').on('render:view', function() {

			// Ensure all target fields start hidden on fresh step renders
			document.querySelectorAll('.accordion-toggle').forEach(function(toggleHeader) {
				let nextElem = toggleHeader.nextElementSibling;
				const isOpen = toggleHeader.classList.contains('is-open');

				while (nextElem && nextElem.classList.contains('accordion-target')) {
					nextElem.style.display = isOpen ? 'block' : 'none';
					nextElem = nextElem.nextElementSibling;
				}
			});

		});
	}

});
