'use strict';

let JWBCPBSettingsMixin = {
	data: function() {
		return {
			pageOptions: window.JWBCPBSettingsConfig.settingsData,
			conditionsOptions: window.JWBCPBSettingsConfig.settingsConditionsData,
			inputBadge: '',
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
			deleteBadgeTrigger: null,
			endpoint: ''
		};
	},

	watch: {
		pageOptions: {
			handler( options ) {

				let prepared = {};

				for ( let option in options ) {
					if ( options.hasOwnProperty( option ) ) {
						prepared[ option ] = options[option]['value'] ?  options[option]['value'] : options[option];
					}
				}

				this.preparedOptions = prepared;

				this.saveOptions();

			},
			deep: true
		}
	},

	methods: {
		addNewBadge: function() {
			if ( this.inputBadge.length > 0 ) {
				const badgesToAdd = this.inputBadge.split(',');

				for ( let badgeToAdd of badgesToAdd ) {
					let badgeLabel = badgeToAdd.trim(),
						badgeValue = badgeLabel.toLowerCase().replace(/[`~!@#$%^&*()|+=?;:'".\\\/]/gi, '').replace( /\s/g, '-' ),
						isValid = true;

					for ( let badge of this.pageOptions.badgesList ) {
						if ( badge.value === badgeValue ) {
							isValid = false;
						}
					}

					if ( ! isValid ) {
						this.$CXNotice.add( {
							message: __( '`' + badgeLabel + '` badge already exist!', 'jwb-custom-product-badges' ),
							type: 'error',
							duration: 3000,
						} );

						continue;
					}

					this.pageOptions.badgesList.push( {
						'value': badgeValue,
						'label': badgeLabel
					} );
				}

				this.endpoint = 'add_badges';
				this.inputBadge = '';
			}
		},

		confirmDeleteBadge: function( badge ) {
			this.deleteBadgeTrigger = badge;
		},

		deleteBadge: function ( value ) {
			this.pageOptions.badgesList = this.pageOptions.badgesList.filter( badge => badge.value !== value );
			this.endpoint = 'delete_badges';
		},

		addNewField: function( item ) {
			item._id = Math.round( Math.random() * 1000000 );

			this.pageOptions.displayConditions.push( item );
			this.endpoint = 'add_condition';
		},

		setFieldProp: function( index, key, value ) {
			let field = this.pageOptions.displayConditions[ index ];

			field[ key ] = value;

			this.pageOptions.displayConditions.splice( index, 1, field );
			this.endpoint = 'update_condition';
		},

		cloneField: function( index) {
			let field = JSON.parse( JSON.stringify( this.pageOptions.displayConditions[ index ] ) );

			field._id = Math.round( Math.random() * 1000000 );

			this.pageOptions.displayConditions.splice( index + 1, 0, field );
			this.endpoint = 'add_condition';
		},

		deleteField: function( index ) {
			this.pageOptions.displayConditions.splice( index, 1 );
			this.endpoint = 'delete_condition';
		},

		isCollapsed: function( parent ) {
			return undefined === parent.collapsed || true === parent.collapsed || 'true' === parent.collapsed;
		},

		saveOptions: function() {

			let self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				url: window.JWBCPBSettingsConfig.settingsRestAPI[ self.endpoint ],
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function() {
					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( response ) {

					self.savingStatus = false;

					if ( 'success' === response.status ) {
						self.$CXNotice.add( {
							message: response.message,
							type: 'success',
							duration: 3000,
						} );

						if ( response?.reload ) {
							setTimeout( function () {
								location.reload( true );
							}, 3000 );
						}
					}

					if ( 'error' === response.status ) {
						self.$CXNotice.add( {
							message: response.message,
							type: 'error',
							duration: 3000,
						} );
					}

				}
			} );

		},
	}
}

Vue.component( 'jwb-custom-product-badges-general', {
	template: '#jet-dashboard-jwb-custom-product-badges-general',
	mixins: [ JWBCPBSettingsMixin ],
} );
