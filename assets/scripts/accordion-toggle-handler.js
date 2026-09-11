/**
 * Ninja Forms Accordion Toggle Handler
 *
 * Handles collapsible content sections within Ninja Forms, including
 * multipart form page transitions. Toggles visibility for fields marked
 * with `.accordion-target` when a `.accordion-toggle` header is clicked.
 *
 * @package     WordPress
 * @subpackage  NinjaForms
 * @author      Your Name
 * @license     GPL-2.0-or-later
 * @link        https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/
 */

document.addEventListener('DOMContentLoaded', function() {

	/**
	 * Updates visibility of target fields sibling to a given toggle container.
	 *
	 * @param {Element} container The .accordion-toggle container element.
	 * @param {boolean} isOpen    Whether the accordion section should be open.
	 */
	function updateTargetVisibility(container, isOpen) {
		const currentNfField = container.closest('nf-field') || container.closest('.nf-cell');
		if (!currentNfField) return;

		let nextNfField = currentNfField.nextElementSibling;
		while (nextNfField) {
			const targetContainer = nextNfField.querySelector('.accordion-target');
			if (!targetContainer) break;

			targetContainer.style.display = isOpen ? 'block' : 'none';
			nextNfField = nextNfField.nextElementSibling;
		}
	}

	/**
	 * Toggles class state and updates associated targets for a header element.
	 *
	 * @param {Element} toggleHeader The clicked element matching .accordion-toggle.
	 */
	function toggleAccordion(toggleHeader) {
		const container = toggleHeader.closest('.nf-field-container');
		if (!container) return;

		container.classList.toggle('is-open');
		const isOpen = container.classList.contains('is-open');

		updateTargetVisibility(container, isOpen);
	}

	// 1. Event Delegation: Listens for clicks anywhere inside .accordion-toggle
	document.body.addEventListener('click', function(e) {
		const toggleHeader = e.target.closest('.accordion-toggle');
		if (toggleHeader) {
			e.preventDefault();
			toggleAccordion(toggleHeader);
		}
	});

	// 2. Ninja Forms Native Event Listener: Handles multipart transitions and page loads
	if (typeof Marionette !== 'undefined' && typeof nfRadio !== 'undefined') {
		nfRadio.channel('form').on('render:view', function() {
			document.querySelectorAll('.nf-field-container.accordion-toggle').forEach(function(container) {
				const isOpen = container.classList.contains('is-open');
				updateTargetVisibility(container, isOpen);
			});
		});
	}

});
