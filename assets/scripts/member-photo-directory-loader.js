/**
 * Member Directory File Conditional Loader Engine
 * Viewport Breaks: Mobile (<= 600px), Tablet (601px - 920px), Desktop (>= 921px)
 */
document.addEventListener("DOMContentLoaded", function() {
	const viewerRoot = document.getElementById('smart-pdf-viewer');
	if (!viewerRoot) return;

	// 1. Gather file targets from the DOM nodes
	const desktopUrl = viewerRoot.getAttribute('data-desktop');
	const tabletUrl  = viewerRoot.getAttribute('data-tablet');
	const mobileUrl  = viewerRoot.getAttribute('data-mobile');

	// 2. Compute viewport metrics using standard browser EM baselines (1em = 16px)
	const currentWidth = window.innerWidth;
	let targetPdfUrl = desktopUrl; // Default fallback track

	if (currentWidth <= 600) {
		targetPdfUrl = mobileUrl;
	} else if (currentWidth > 600 && currentWidth <= 920) {
		targetPdfUrl = tabletUrl;
	} else {
		targetPdfUrl = desktopUrl;
	}

	// 3. Inject exactly ONE embed element into the page
	viewerRoot.innerHTML = `
        <object data="${targetPdfUrl}" type="application/pdf" width="100%" height="800px" style="border: none;">
            <div class="pdf-fallback-message">
                <p>Your web browser does not support inline PDF file previews.</p>
                <a href="${targetPdfUrl}" class="button" target="_blank">Click here to open the member photo directory.</a>
            </div>
        </object>
    `;
});
