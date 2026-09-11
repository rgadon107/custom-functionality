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

		// Stop scanning if the next row is another accordion toggle or has no target
		if (!targetContainer && nextRow.querySelector('.accordion-toggle')) {
			break;
		}

		if (targetContainer) {
			if (isOpen) {
				targetContainer.style.setProperty('display', 'block', 'important');
			} else {
				targetContainer.style.setProperty('display', 'none', 'important');
			}
		}

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

	// Explicitly check current state before toggling
	const isCurrentlyOpen = container.classList.contains('is-open');
	const newState = !isCurrentlyOpen;

	if (newState) {
		container.classList.add('is-open');
	} else {
		container.classList.remove('is-open');
	}

	updateTargetVisibility(container, newState);
}

/**
 * Binds global capture-phase click listener for accordion elements.
 */
function initAccordionListener() {
	document.addEventListener('click', function(e) {
		const toggleHeader = e.target.closest('.accordion-toggle');
		if (toggleHeader) {
			e.preventDefault();
			toggleAccordion(toggleHeader);
		}
	}, true);
}

// Bind listener safely after DOM body initialization
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initAccordionListener);
} else {
	initAccordionListener();
}
