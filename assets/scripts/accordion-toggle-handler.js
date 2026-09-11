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

(function() {

	/**
	 * Updates visibility of target fields in adjacent rows relative to a toggle container.
	 *
	 * @param {Element} container The .accordion-toggle container element.
	 * @param {boolean} isOpen    Whether the accordion section should be open.
	 */
	function updateTargetVisibility(container, isOpen) {
		const currentRow = container.closest('.nf-row');
		if (!currentRow) return;

		let nextRow = currentRow.nextElementSibling;
		while (nextRow) {
			const targetContainer = nextRow.querySelector('.accordion-target');
			if (!targetContainer) break;

			targetContainer.style.display = isOpen ? 'block' : 'none';
			nextRow = nextRow.nextElementSibling;
		}
	}

	/**
	 * Toggles class state and updates associated targets for a header element.
	 *
	 * @param {Element} toggleHeader The element matching .accordion-toggle.
	 */
	function toggleAccordion(toggleHeader) {
		const container = toggleHeader.closest('.nf-field-container');
		if (!container) return;

		container.classList.toggle('is-open');
		const isOpen = container.classList.contains('is-open');

		updateTargetVisibility(container, isOpen);
	}

	// 1. Direct Root-Level Click Delegator (Fires immediately on any page click)
	document.addEventListener('click', function(e) {
		const toggleHeader = e.target.closest('.accordion-toggle');
		if (toggleHeader) {
			e.preventDefault();
			toggleAccordion(toggleHeader);
		}
	}, true);

	// 2. Initial state sync on form render / multi-step change
	function syncInitialStates() {
		document.querySelectorAll('.nf-field-container.accordion-toggle').forEach(function(container) {
			const isOpen = container.classList.contains('is-open');
			updateTargetVisibility(container, isOpen);
		});
	}

	// Subscribe state sync to Ninja Forms radio events if available
	if (typeof nfRadio !== 'undefined') {
		nfRadio.channel('form').on('render:view after:renderFields', function() {
			setTimeout(syncInitialStates, 50);
		});
		nfRadio.channel('fields').on('render:view', function() {
			setTimeout(syncInitialStates, 50);
		});
	} else {
		document.addEventListener('DOMContentLoaded', syncInitialStates);
	}

})();
