/**
 * Re-maps the keyboard Enter key to act like a Tab (Next Field) key,
 * while preserving Enter key functionality for textarea fields. Prevent early
 * form submit when moving through a form using the Return key. Modernized with
 * ES6 standards and strict comparison operations.
 *
 *  @since      1.0.0
 *  @package    CustomFunctionalityPlugin
 *  @subpackage Assets/Scripts
 *  @author     Robert Gadon <https://github/rgadon107/custom-functionality>
 */
jQuery(document).on('nfInit', function() {

	// Use event delegation on the document for robustness
	jQuery(document).on('keydown', '.nf-form-cont input, .nf-form-cont select', function(e) {

		// Check for 'Enter' using the modern key property or the legacy which property
		const isEnterKey = (e.key === 'Enter' || e.which === 13);

		if (isEnterKey) {
			// 1. Stop the form from submitting
			e.preventDefault();

			// 2. Scope the search to the current form only
			const $form = jQuery(this).closest('.nf-form-cont');

			// 3. Find all focusable elements that aren't buttons
			const focusable = $form.find('input:not([type="submit"]):not([type="button"]), select, textarea').filter(':visible');

			// 4. Identify the current position
			const currentIndex = focusable.index(this);

			// 5. If there is a next field, move focus to it
			if (currentIndex > -1 && currentIndex < focusable.length - 1) {
				focusable.eq(currentIndex + 1).focus();
			}

			console.log(`Enter key intercepted at index: ${currentIndex}. Moving to next field.`);
			return false;
		}
	});

	console.log('ES6 Handle: nf-prevent-enter-submit active.');
});
