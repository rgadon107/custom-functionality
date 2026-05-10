/**
 * Prevents the page from jumping to the bottom when a CoBlocks
 * accordion item is opened.
 */
document.addEventListener('DOMContentLoaded', function() {
	// Select all CoBlocks accordion summary headers
	const accordionSummaries = document.querySelectorAll('.wp-block-coblocks-accordion-item summary');

	accordionSummaries.forEach(summary => {
		summary.addEventListener('click', function() {
			// 1. Capture the scroll position immediately at the moment of click
			const startingScrollPos = window.pageYOffset || document.documentElement.scrollTop;

			// 2. Wait for the browser to process the accordion opening (the layout shift)
			// Using a double requestAnimationFrame is more reliable than a timeout
			requestAnimationFrame(() => {
				requestAnimationFrame(() => {
					const currentScrollPos = window.pageYOffset || document.documentElement.scrollTop;

					// 3. If the browser jumped more than 100 pixels away from where we started
					if (Math.abs(currentScrollPos - startingScrollPos) > 100) {
						// Snap the browser back to where the user was
						window.scrollTo({
							top: startingScrollPos,
							behavior: 'auto' // Use 'auto' or 'instant' to prevent a "sliding" fight
						});
					}
				});
			});
		});
	});
});
