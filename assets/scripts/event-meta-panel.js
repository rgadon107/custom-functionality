/**
 * Event Metadata Sidebar Panel for the WordPress Block Editor.
 *
 * Registers a custom Gutenberg plugin sidebar panel (`gcm-event-meta-panel`)
 * on the `events` custom post type edit screen. Exposes input controls for
 * temporal, location, presenter, and logistics metadata keys registered via
 * `register_post_meta()` with `show_in_rest` enabled, syncing values in real
 * time with the Block Editor data store and Block Bindings API.
 *
 * Enqueued script dependencies (registered via PHP in /src/asset/handler.php):
 * - wp-plugins
 * - wp-editor
 * - wp-components
 * - wp-core-data
 * - wp-data
 * - wp-element
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage Assets\Scripts
 * @since      2.4.0 Intial release.
 */

( function ( wp ) {
	const { registerPlugin } = wp.plugins;
	const { PluginDocumentSettingPanel } = wp.editor;
	const { TextControl, TextareaControl, PanelRow } = wp.components;
	const { useEntityProp } = wp.coreData;
	const { useSelect } = wp.data;
	const { createElement, Fragment } = wp.element;

	const EventMetaPanel = () => {
		// Verify we are currently editing an 'events' post type
		const postType = useSelect(
			( select ) => select( 'core/editor' ).getCurrentPostType(),
			[]
		);

		if ( postType !== 'events' ) {
			return null;
		}

		// Bind to the post's registered REST metadata
		const [ meta, setMeta ] = useEntityProp( 'postType', 'events', 'meta' );

		// Guard against meta not yet resolving on initial render
		if ( ! meta ) {
			return null;
		}

		// Helper to update a single meta key immutably
		const updateMeta = ( key, value ) => {
			setMeta( { ...meta, [ key ]: value } );
		};

		return createElement(
			Fragment,
			null,

			// Panel 1: Temporal and Location Data
			createElement(
				PluginDocumentSettingPanel,
				{
					name: 'gcm-event-temporal-location-panel',
					title: 'Event Date & Location',
					icon: 'calendar-alt',
				},
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Start Date',
						help: 'Format: YYYY-MM-DD',
						type: 'date',
						value: meta._event_start_date || '',
						onChange: ( val ) => updateMeta( '_event_start_date', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Stop Date',
						help: 'Optional multi-day end date (YYYY-MM-DD)',
						type: 'date',
						value: meta._event_stop_date || '',
						onChange: ( val ) => updateMeta( '_event_stop_date', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Location Name',
						value: meta._event_location_name || '',
						onChange: ( val ) => updateMeta( '_event_location_name', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextareaControl, {
						label: 'Location Address',
						rows: 3,
						value: meta._event_location_address || '',
						onChange: ( val ) => updateMeta( '_event_location_address', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Google Maps URL',
						type: 'url',
						value: meta._event_location_map_url || '',
						onChange: ( val ) => updateMeta( '_event_location_map_url', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Registration Opens',
						help: 'Leave blank for immediate registration.',
						type: 'datetime-local',
						value: ( meta._event_registration_start_date || '' ).replace( ' ', 'T' ),
						onChange: ( val ) => updateMeta( '_event_registration_start_date', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Registration Closes',
						help: 'Date and time when the registration button closes.',
						type: 'datetime-local',
						value: ( meta._event_registration_stop_date || '' ).replace( ' ', 'T' ),
						onChange: ( val ) => updateMeta( '_event_registration_stop_date', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextareaControl, {
						label: 'Pre-Registration Message',
						help: 'Displayed before registration opens (supports basic HTML). Leave blank to display default message.',
						rows: 3,
						value: meta._event_registration_before_message || '',
						onChange: ( val ) => updateMeta( '_event_registration_before_message', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextareaControl, {
						label: 'Registration Closed Message',
						help: 'Displayed after registration closes (supports basic HTML). Leave blank to display default message.',
						rows: 3,
						value: meta._event_registration_after_message || '',
						onChange: ( val ) => updateMeta( '_event_registration_after_message', val ),
					} )
				),
			),

			// Panel 2: Presenter Group
			createElement(
				PluginDocumentSettingPanel,
				{
					name: 'gcm-event-presenter-panel',
					title: 'Presenter Details',
					icon: 'businessman',
				},
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Presenter Name',
						value: meta._event_speaker_name || '',
						onChange: ( val ) => updateMeta( '_event_speaker_name', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Presenter Title',
						value: meta._event_speaker_title || '',
						onChange: ( val ) => updateMeta( '_event_speaker_title', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Presenter Organization',
						value: meta._event_speaker_organization || '',
						onChange: ( val ) => updateMeta( '_event_speaker_organization', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Presenter Website URL',
						help: 'Links to the Presenter Organization.',
						type: 'url',
						value: meta._event_speaker_url || '',
						onChange: ( val ) => updateMeta( '_event_speaker_url', val ),
					} )
				)
			),

			// Panel 3: Logistics & Scheduling
			createElement(
				PluginDocumentSettingPanel,
				{
					name: 'gcm-event-logistics-panel',
					title: 'Logistics & Scheduling',
					icon: 'clipboard',
				},
				createElement(
					PanelRow,
					null,
					createElement( TextareaControl, {
						label: 'Event Schedule',
						help: 'Field entries support basic HTML formatting.',
						rows: 4,
						value: meta._event_schedule || '',
						onChange: ( val ) => updateMeta( '_event_schedule', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextareaControl, {
						label: 'Dinner Preregistration Notice',
						help: 'Announce when dinner registration for the event will close.',
						rows: 3,
						value: meta._event_preregistration_notice || '',
						onChange: ( val ) => updateMeta( '_event_preregistration_notice', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Volunteer Signup URL',
						help: 'Copy and paste the URL to the volunteer sign-up link.',
						type: 'url',
						value: meta._event_volunteer_url || '',
						onChange: ( val ) => updateMeta( '_event_volunteer_url', val ),
					} )
				),
				createElement(
					PanelRow,
					null,
					createElement( TextControl, {
						label: 'Event HTML Anchor ID',
						help: 'Optional. Omit # preceding ID. If blank, the anchor will default to the event URL slug ( /events/{slug} ).',
						value: meta._event_anchor_id || '',
						onChange: ( val ) => updateMeta( '_event_anchor_id', val ),
					} )
				)
			)
		);
	};

	registerPlugin( 'gcm-event-meta-panel', {
		render: EventMetaPanel,
	} );
} )( window.wp );
