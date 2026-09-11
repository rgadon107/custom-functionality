/**
 * Ninja Forms Accordion Toggle Handler
 *
 * Handles collapsible content sections within Ninja Forms, including
 * multipart form page transitions. Toggles visibility for fields marked
 * with `.accordion-target` when a `.accordion-toggle` header is clicked.
 *
 * @package     WordPress
 * @subpackage  NinjaForms
 * @author      rgadon107\CustomFunctionalityPlugin
 * @license     GPL-2.0-or-later
 * @link        https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/
 */

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

// Global capture-phase click delegator
document.addEventListener('click', function(e) {
	const toggleHeader = e.target.closest('.accordion-toggle');
	if (toggleHeader) {
		e.preventDefault();
		toggleAccordion(toggleHeader);
	}
}, true);
