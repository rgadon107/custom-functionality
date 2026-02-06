/**
 * Re-maps the keyboard Enter key to act like a Tab (Next Field) key,
 * while preserving Enter key functionality for textarea fields. Prevent early
 * form submit when moving through a form using the Return key.
 */
jQuery(document).on('nfInit', function() {

	// Use delegation on the document so it works even if forms reload via AJAX
	jQuery(document).on('keydown', '.nf-form-cont input, .nf-form-cont select', function(e) {

		if (e.which === 13) {
			// 1. Prevent the actual form submission
			e.preventDefault();

			// 2. Find all focusable elements in this specific form
			var $form = jQuery(this).closest('.nf-form-cont');
			var focusable = $form.find('input:not([type="submit"]):not([type="button"]), select, textarea').filter(':visible');

			// 3. Find where we are currently
			var currentIndex = focusable.index(this);

			// 4. If there is a next field, move to it
			if (currentIndex > -1 && currentIndex < focusable.length - 1) {
				focusable.eq(currentIndex + 1).focus();
			}

			return false;
		}
	});
});
