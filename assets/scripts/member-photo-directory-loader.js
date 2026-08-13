/**
 * Member Directory File Conditional Loader Engine
 * Viewport Breaks: Mobile (<= 600px), Tablet (601px - 920px), Desktop (>= 921px)
 */
document.addEventListener("DOMContentLoaded", function() {
	// Select all potential viewer containers (supports both class and ID hooks)
	const viewerRoots = document.querySelectorAll('.smart-pdf-viewer, #smart-pdf-viewer');
	if (!viewerRoots.length) return;

	/**
	 * Injects the responsive PDF <object> into each target viewer container
	 */
	function initPdfViewers() {
		viewerRoots.forEach(function(viewerRoot) {
			// Prevent duplicate initialization
			if (viewerRoot.querySelector('object') || viewerRoot.querySelector('.pdf-error-message')) return;

			const desktopUrl = viewerRoot.getAttribute('data-desktop');
			const tabletUrl  = viewerRoot.getAttribute('data-tablet');
			const mobileUrl  = viewerRoot.getAttribute('data-mobile');

			const currentWidth = window.innerWidth;
			let targetPdfUrl;

			// Viewport breakpoint selection
			if (currentWidth <= 600) {
				targetPdfUrl = mobileUrl;
			} else if (currentWidth <= 920) {
				targetPdfUrl = tabletUrl;
			} else {
				targetPdfUrl = desktopUrl;
			}

			// Safety check: Render error UI if configuration URLs fail to resolve
			if (!targetPdfUrl) {
				console.warn('[Member Photo Directory] PDF URL resolved to empty. Check configuration settings.');
				viewerRoot.innerHTML = `
                    <div class="pdf-error-message">
                        <p><strong>Unable to load directory.</strong> The requested photo directory file could not be found or configured.</p>
                    </div>
                `;
				return;
			}

			// Embed the PDF document
			viewerRoot.innerHTML = `
                <object data="${targetPdfUrl}" type="application/pdf" width="100%" height="800px" style="border: none;">
                    <div class="pdf-fallback-message">
                        <p>Your web browser does not support inline PDF previews.</p>
                        <a href="${targetPdfUrl}" class="button" target="_blank">Click here to open the photo directory file directly.</a>
                    </div>
                </object>
            `;
		});
	}

	// Process viewers based on structural parent containers
	viewerRoots.forEach(function(viewerRoot) {
		const detailsParent = viewerRoot.closest('details');

		if (detailsParent) {
			// If inside a <details> block, defer rendering until toggled open
			detailsParent.addEventListener('toggle', function() {
				if (detailsParent.open) {
					initPdfViewers();
				}
			});

			// Handle case where <details open> is set on page load
			if (detailsParent.open) {
				initPdfViewers();
			}
		} else {
			// Standalone embed: Initialize immediately
			initPdfViewers();
		}
	});

	// Event listener support for Popup Maker modals
	if (window.jQuery) {
		jQuery(document).on('pumAfterOpen', function() {
			initPdfViewers();
		});
	}
});
