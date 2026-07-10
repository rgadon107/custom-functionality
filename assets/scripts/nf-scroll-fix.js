/**
 * Ninja Forms Scroll Fix on Form Submission
 *
 * Handles frontend layout stabilization for Ninja Forms.
 *
 * Intercepts a multi-part Ninja Forms form submission event to halt the automated
 * browser focus scroll that forces the viewport down to the footer,
 * smoothly resetting the user's view to the success/response wrapper.
 *
 * @package    CustomFunctionality
 * @subpackage Assets/JS
 * @author     Robert A. Gadon
 * @since      1.0.0
 * @link       https://github.com/rgadon107/custom-functionality
 */
jQuery(document).on('nfFormSubmitEnd', function(event, response, formID) {
	// 1. Immediately kill the browser's attempt to focus snap to an anchor/hash
	if (window.location.hash.includes('nf-form') || window.location.hash.includes('ninja')) {
		history.replaceState(null, null, ' ');
	}

	// 2. Override the viewport placement by instantly freezing or resetting the scroll position
	var formWrapper = jQuery('#nf-form-' + formID + '-cont');
	if (formWrapper.length) {
		jQuery('html, body').stop().animate({
			scrollTop: formWrapper.offset().top - 120
		}, 200); // A rapid, gentle 200ms stabilization instead of a violent snap
	}
});
