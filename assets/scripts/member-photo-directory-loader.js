/**
 * Member Directory File Conditional Loader Engine
 * Viewport Breaks: Mobile (<= 600px), Tablet (601px - 920px), Desktop (>= 921px)
 */
document.addEventListener("DOMContentLoaded", function() {
	const viewerRoot = document.getElementById('smart-pdf-viewer');
	if (!viewerRoot) return;

	// Find the parent <details> block if one exists
	const detailsParent = viewerRoot.closest('details');

	// Helper function to inject the PDF object
	function loadPdfObject() {
		// Prevent loading multiple times if already initialized
		if (viewerRoot.querySelector('object')) return;

		const desktopUrl = viewerRoot.getAttribute('data-desktop');
		const tabletUrl  = viewerRoot.getAttribute('data-tablet');
		const mobileUrl  = viewerRoot.getAttribute('data-mobile');

		const currentWidth = window.innerWidth;
		let targetPdfUrl;

		if (currentWidth <= 600) {
			targetPdfUrl = mobileUrl;
		} else if (currentWidth > 600 && currentWidth <= 920) {
			targetPdfUrl = tabletUrl;
		} else {
			targetPdfUrl = desktopUrl;
		}

		// Safety check: Don't embed if URLs are empty
		if (!targetPdfUrl) {
			viewerRoot.innerHTML = '<p class="pdf-error">Unable to read the current configuration file path. Visit `/src/configuration/shortcodes/member-photo-directory.php` and inspect the file configuration.</p>';
			return;
		}

		viewerRoot.innerHTML = `
            <object data="${targetPdfUrl}" type="application/pdf" width="100%" height="800px" style="border: none;">
                <div class="pdf-fallback-message">
                    <p>Your web browser does not support inline PDF previews.</p>
                    <a href="${targetPdfUrl}" class="button" target="_blank">Click here to open the photo directory file.</a>
                </div>
            </object>
        `;
	}

	if (detailsParent) {
		// If wrapped in <details>, wait for the user to toggle it open
		detailsParent.addEventListener('toggle', function() {
			if (detailsParent.open) {
				loadPdfObject();
			}
		});

		// If <details> is already open on page load (e.g. <details open>)
		if (detailsParent.open) {
			loadPdfObject();
		}
	} else {
		// Standalone shortcode (not inside a <details> block)
		loadPdfObject();
	}
});
