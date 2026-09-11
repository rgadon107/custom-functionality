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

function updateTargetVisibility(container, isOpen) {
	const currentRow = container.closest('.nf-row');
	if (!currentRow) return;

	let nextRow = currentRow.nextElementSibling;
	while (nextRow) {
		const targetContainer = nextRow.querySelector('.accordion-target');
		if (!targetContainer) break;

		targetContainer.style.setProperty('display', isOpen ? 'block' : 'none', 'important');
		nextRow = nextRow.nextElementSibling;
	}
}

function toggleAccordion(toggleHeader) {
	const container = toggleHeader.closest('.nf-field-container');
	if (!container) return;

	container.classList.toggle('is-open');
	const isOpen = container.classList.contains('is-open');

	updateTargetVisibility(container, isOpen);
}

function initAccordionListener() {
	console.log('--- ACCORDION LISTENER ATTACHED TO DOCUMENT ---');
	document.addEventListener('click', function(e) {
		const toggleHeader = e.target.closest('.accordion-toggle');
		if (toggleHeader) {
			console.log('Accordion header clicked!');
			e.preventDefault();
			toggleAccordion(toggleHeader);
		}
	}, true);
}

// Ensure event listener binds AFTER document body exists
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initAccordionListener);
} else {
	initAccordionListener();
}
