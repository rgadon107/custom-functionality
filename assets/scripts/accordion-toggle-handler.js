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

	/**
	 * Scans and attaches click events to all current accordion toggles.
	 */
	function initAccordions() {
		document.querySelectorAll('.nf-field-container.accordion-toggle').forEach(function(container) {
			const clickableElement = container.querySelector('h3') || container;

			if (clickableElement.dataset.accordionBound === 'true') return;
			clickableElement.dataset.accordionBound = 'true';
			clickableElement.style.cursor = 'pointer';

			clickableElement.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				toggleAccordion(container);
			});

			const isOpen = container.classList.contains('is-open');
			updateTargetVisibility(container, isOpen);
		});
	}

	// 1. Capture-phase fallback listener for clicks at document root
	document.addEventListener('click', function(e) {
		const toggleHeader = e.target.closest('.accordion-toggle');
		if (toggleHeader) {
			toggleAccordion(toggleHeader);
		}
	}, true);

	// 2. Ninja Forms Marionette & Backbone Event Subscriptions
	function setupRadioListeners() {
		if (typeof nfRadio === 'undefined') return;

		// Triggers when multi-part steps change or view re-renders
		nfRadio.channel('form').on('render:view after:renderFields', function() {
			setTimeout(initAccordions, 50);
		});

		// Triggers as individual field views attach to DOM
		nfRadio.channel('fields').on('render:view', function() {
			setTimeout(initAccordions, 50);
		});
	}

	// Initialize immediately if DOM is ready, or wait for ready state
	if (document.readyState === 'interactive' || document.readyState === 'complete') {
		setupRadioListeners();
		initAccordions();
	} else {
		document.addEventListener('DOMContentLoaded', function() {
			setupRadioListeners();
			initAccordions();
		});
	}

})();
