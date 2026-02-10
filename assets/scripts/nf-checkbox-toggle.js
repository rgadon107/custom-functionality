/**
 * Ninja Forms Checkbox Toggle Script.
 *  Intercepts binary 0/1 values rendered by Ninja Forms merge tags in HTML fields
 *  and converts them to "Yes" or "No" display strings. This script uses a
 *  MutationObserver to ensure values are updated even if the form re-renders
 *  dynamically via AJAX.
 *
 * @since      1.0.0
 * @package    CustomFunctionalityPlugin
 * @subpackage Assets/Scripts
 * @author     Robert Gadon <https://github/rgadon107/custom-functionality>
 */

(function($) {

	/**
	 *	Add a Ninja Forms merge tag key below to target additional checkboxes.
	 *  Merge tags are preceded by the term 'field:'. Merge tags must be added to the array as strings.
	 *
	 * @type {string[]} targetKeys List of Ninja Forms merge tag data-keys to monitor.
	 */
	const targetKeys = [
		'field:veg_dinner_order_ticket_purchaser_1768877697594',
		'field:gf_dinner_order_ticket_purchaser_1768877759742',
		'field:veg_dinner_order_1st_added_attendee_1768881080131',
		'field:gf_dinner_order_1st_added_attendee_1768881004789',
		'field:veg_dinner_order_2nd_added_attendee_1768881242386',
		'field:gf_dinner_order_2nd_added_attendee_1768881245576',
	];

	/**
	 * Scans the DOM for specific Ninja Forms data-key spans and swaps
	 * binary 0/1 text with human-readable Yes/No strings.
	 *
	 * @return {void}
	 */
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

	/**
	 * Initializes the MutationObserver to watch for changes within the form content.
	 * This ensures the swap happens after Ninja Forms completes its dynamic rendering.
	 *
	 * @return {void}
	 */
	const initObserver = () => {
		const target = document.querySelector('.nf-form-content');
		if (!target) return;

		/**
		 * MutationObserver instance to handle dynamic updates.
		 */
		const observer = new MutationObserver(() => {
			performSwap();
		});

		observer.observe(target, {
			childList: true,
			subtree: true,
			characterData: true
		});

		// Initial execution for static content.
		performSwap();
	};

	/**
	 * Polls for the existence of the Ninja Forms container before initializing.
	 *
	 * @type {number} checkExist
	 */
	const checkExist = setInterval(() => {
		if ($('.nf-form-content').length) {
			initObserver();
			clearInterval(checkExist);
		}
	}, 500);

})(jQuery);
