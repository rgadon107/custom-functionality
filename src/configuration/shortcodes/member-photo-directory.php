<?php
/**
 * Configuration for [member_photo_directory] shortcode.
 *
 * Location: /src/configuration/shortcodes/member-photo-directory.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir      = wp_upload_dir();
$upload_base_url = trailingslashit( $upload_dir['baseurl'] ) . '2026/08/';

return array(
	'desktop_url' => $upload_base_url . '2026-GCM-Photo-Directory-Desktop.pdf',
	'tablet_url'  => $upload_base_url . '2026-GCM-Photo-Directory-Tablet.pdf',
	'mobile_url'  => $upload_base_url . '2026-GCM-Photo-Directory-Mobile.pdf',
);
