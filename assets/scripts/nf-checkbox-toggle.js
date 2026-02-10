/**
 * Handles the front-end display toggle for Ninja Forms checkbox merge tags.
 *
 * Intercepts binary 0/1 values in HTML fields and converts them to Yes/No
 * strings by observing DOM mutations on specific data-key spans.
 *
 * @since      1.0.0
 * @package    CustomFunctionalityPlugin
 * @subpackage Assets/Scripts
 * @author     Robert Gadon <https://github/rgadon107/custom-functionality>
 */

(function($) {

	/* Add a Ninja Forms merge tag key below to target additional checkboxes. */
	/* Merge tags are preceded by the term 'field:'. Merge tags must be added to the array as strings.  */
	const targetKeys = [
		'field:veg_dinner_order_ticket_purchaser_1768877697594',
		'field:gf_dinner_order_ticket_purchaser_1768877759742',
		'field:veg_dinner_order_1st_added_attendee_1768881080131',
		'field:gf_dinner_order_1st_added_attendee_1768881004789',
		'field:veg_dinner_order_2nd_added_attendee_1768881242386',
		'field:gf_dinner_order_2nd_added_attendee_1768881245576',
	];

	const performSwap = () => {
		targetKeys.forEach(key => {
			// Find the specific span Ninja Forms created for this merge tag
			$(`span[data-key="${key}"]`).each(function() {
				const $el = $(this);
				const val = $el.text().trim();

				if (val === '0') {
					$el.text('No');
				} else if (val === '1') {
					$el.text('Yes');
				}
			});
		});
	};

	const initObserver = () => {
		const target = document.querySelector('.nf-form-content');
		if (!target) return;

		// Watch for Ninja Forms re-rendering the review section
		const observer = new MutationObserver(() => {
			performSwap();
		});

		observer.observe(target, {
			childList: true,
			subtree: true,
			characterData: true
		});

		// Run immediately
		performSwap();
	};

	// Wait for Ninja Forms to load the content
	const checkExist = setInterval(() => {
		if ($('.nf-form-content').length) {
			initObserver();
			clearInterval(checkExist);
		}
	}, 500);

})(jQuery);
