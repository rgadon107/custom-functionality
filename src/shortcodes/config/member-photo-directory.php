<?php
/**
 * Configuration for [member_photo_directory] shortcode.
 *
 * @since 2.2.0 Initial release.
 * @since 2.3.1 Updated to use a new asset versioning helper function.
 *
 * Location: /src/shortcodes/member-photo-directory.php
 */

use function gardenClubOfMpls\CustomFunctionalityPlugin\Asset\_get_media_asset_version;

$upload_dir      = wp_upload_dir();
$upload_base_url = trailingslashit( $upload_dir['baseurl'] ) . '2026/08/';

return array(
	'desktop_url' => _get_media_asset_version( $upload_base_url . '2026-GCM-Photo-Directory-Desktop.pdf' ),
	'tablet_url'  => _get_media_asset_version( $upload_base_url . '2026-GCM-Photo-Directory-Tablet.pdf' ),
	'mobile_url'  => _get_media_asset_version( $upload_base_url . '2026-GCM-Photo-Directory-Mobile.pdf' ),
);
