<?php
/**
 * Quick Edit Fieldset View for the 'events' Custom Post-Type.
 *
 * Rendered via `quick_edit_custom_box` in `/src/admin/events.php`.
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage Source\Admin\Views
 * @since      2.4.1 Initial release.
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<fieldset class="inline-edit-col-right">
	<div class="inline-edit-col">
		<label>
			<span class="title"><?php esc_html_e( 'Start Date', 'custom-functionality' ); ?></span>
			<span class="input-text-wrap">
				<input type="date" name="event_start_date" value="" />
			</span>
		</label>
	</div>
</fieldset>
