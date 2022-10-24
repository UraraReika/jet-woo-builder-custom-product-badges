'use strict';

let JWBCPBSettingsMixin = {
	data: function() {
		return {
			pageOptions: window.JWBCPBSettingsConfig.settingsData,
			inputBadge: '',
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
			deleteBadgeTrigger: null,
			conditions: []
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

				let badgeValue = this.inputBadge.toLowerCase().replace( /\s/g, '-' ),
					isValid = true;

				for ( let badge of this.pageOptions.badgesList ) {
					if ( badge.value === badgeValue ) {
						isValid = false;
					}
				}

				if ( isValid ) {
					this.pageOptions.badgesList.push( {
						'value': badgeValue,
						'label': this.inputBadge
					} );

					this.pageOptions.actionType['value'] = 'add';
					this.inputBadge = '';
				} else {
					this.$CXNotice.add( {
						message: __( 'This badge already exist!', 'jwb-custom-product-badges' ),
						type: 'error',
						duration: 3000,
					} );
				}

			}
		},

		confirmDeleteBadge: function( badge ) {
			this.deleteBadgeTrigger = badge;
		},

		deleteBadge: function ( value ) {
			this.pageOptions.badgesList = this.pageOptions.badgesList.filter( badge => badge.value !== value );
			this.pageOptions.actionType['value'] = 'remove';
		},

		saveOptions: function() {

			let self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				url: window.JWBCPBSettingsConfig.settingsApiUrl,
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

		addNewField: function( event, props, parent, callback ) {

			props = props || [];

			var field = {};

			for (var i = 0; i < props.length; i++) {
				field[ props[ i ] ] = '';
			}

			field._id = Math.round( Math.random() * 1000000 );
			field.collapsed = false;

			parent.push( field );

			if ( callback && 'function' === typeof callback ) {
				callback( field, parent );
			}

		},
		setFieldProp: function( id, key, value, parent ) {

			let index = this.searchByID( id, parent );

			if ( false === index ) {
				return;
			}

			let field = parent[ index ];

			field[ key ] = value;

			parent.splice( index, 1, field );

		},
		cloneField: function( index, id, parent, callback ) {

			let field = JSON.parse( JSON.stringify( parent[ index ] ) );

			field.collapsed = false;
			field._id = Math.round( Math.random() * 1000000 );

			parent.splice( index + 1, 0, field );

			if ( callback && 'function' === typeof callback ) {
				callback( field, parent, id );
			}

		},
		deleteField: function( index, id, parent, callback ) {

			index = this.searchByID( id, parent );

			if ( false === index ) {
				return;
			}

			parent.splice( index, 1 );

			if ( callback && 'function' === typeof callback ) {
				callback( id, index, parent );
			}

		},
		isCollapsed: function( parent ) {
			if ( undefined === parent.collapsed || true === parent.collapsed ) {
				return true;
			} else {
				return false;
			}
		},
		searchByID: function( id, list ) {

			for ( var i = 0; i < list.length; i++ ) {
				if ( id == list[ i ]._id ) {
					return i;
				}
			}

			return false;

		}
	}
}

Vue.component( 'jwb-custom-product-badges-general', {
	template: '#jet-dashboard-jwb-custom-product-badges-general',
	mixins: [ JWBCPBSettingsMixin ],
} );
