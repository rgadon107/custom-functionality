/**
 * Auction Item Meta-Panel Editor Extension.
 *
 * Registers a custom Document Setting Panel in the Block Editor sidebar
 * to manage custom post metadata for the 'auction_item' post-type.
 *
 * @package GardenClubOfMpls\CustomFunctionality
 * @since   2.3.1
 *
 * @typedef {Object} WPGlobal
 * @property {Object} plugins
 * @property {Object} editPost
 * @property {Object} components
 * @property {Object} coreData
 * @property {Object} data
 * @property {Object} element
 *
 * @param {WPGlobal} wp WordPress global namespace object.
 */

( function( wp ) {
	const registerPlugin = wp.plugins.registerPlugin;
	const PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	const TextControl = wp.components.TextControl;
	const TextareaControl = wp.components.TextareaControl;
	const useEntityProp = wp.coreData.useEntityProp;
	const useSelect = wp.data.useSelect;
	const el = wp.element.createElement;

	/**
	 * Renders the Auction Item Details sidebar panel component.
	 *
	 * Binds input controls to post metadata using the useEntityProp hook
	 * and restricts execution exclusively to the 'auction_item' CPT.
	 *
	 * @component
	 * @return {Element|null} The React element tree for the panel, or null if not editing an auction item.
	 */
	const AuctionItemMetaPanel = function() {
		const postType = useSelect( function( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		} );

		const entityProp = useEntityProp( 'postType', postType, 'meta' );
		const meta = entityProp[0];
		const setMeta = entityProp[1];

		// Restrict execution to auction_item CPT
		if ( postType !== 'auction_item' || ! meta ) {
			return null;
		}

		/**
		 * Helper function to update individual metadata key-value pairs.
		 *
		 * @param {string} key   The metadata field key to update.
		 * @param {*}      value The new value to set for the key.
		 * @return {void}
		 */
		const updateMeta = function( key, value ) {
			const newMeta = {};
			newMeta[ key ] = value;
			setMeta( Object.assign( {}, meta, newMeta ) );
		};

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'auction-item-meta-panel',
				title: 'Auction Item Details',
				icon: 'tickets-alt',
			},
			el( TextareaControl, {
				label: 'Full Item Description',
				value: meta.auction_item_description || '',
				onChange: function( value ) { updateMeta( 'auction_item_description', value ); },
				help: 'Maximum 650 characters',
			} ),
			el( TextControl, {
				label: 'Donor - First Name',
				value: meta.donor_first_name || '',
				onChange: function( value ) { updateMeta( 'donor_first_name', value ); },
			} ),
			el( TextControl, {
				label: 'Donor - Last Name',
				value: meta.donor_last_name || '',
				onChange: function( value ) { updateMeta( 'donor_last_name', value ); },
			} ),
			el( TextControl, {
				label: 'Estimated Item Value (USD)',
				type: 'number',
				value: meta.auction_item_value || '',
				onChange: function( value ) { updateMeta( 'auction_item_value', value ); },
			} ),
			el( TextControl, {
				label: 'Minimum Opening Bid (USD)',
				type: 'number',
				value: meta.auction_item_minimum_bid || '',
				onChange: function( value ) { updateMeta( 'auction_item_minimum_bid', value ); },
			} )
		);
	};

	registerPlugin( 'auction-item-meta-panel', {
		render: AuctionItemMetaPanel,
	} );
} )( window.wp );
