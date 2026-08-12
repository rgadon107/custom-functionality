<?php
/**
 * Three-Tier Member Photo Directory File Embed View.
 *
 * This HTML view loads the member photo directory file based on screen width detected
 * * by `/assets/scripts/member-photo-directory-loader.js`.
 *
 * @var string $desktop_pdf_url
 * @var string $tablet_pdf_url
 * @var string $mobile_pdf_url
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="smart-pdf-container">
	<!-- The JavaScript engine read-hooks these properties and injects the target file here -->
	<div id="smart-pdf-viewer"
	     data-desktop="<?php echo esc_url( $desktop_pdf_url ); ?>"
	     data-tablet="<?php echo esc_url( $tablet_pdf_url ); ?>"
	     data-mobile="<?php echo esc_url( $mobile_pdf_url ); ?>">

		<div class="pdf-loading-placeholder">
			<p>Loading the member photo directory file...</p>
		</div>
	</div>
</div>
