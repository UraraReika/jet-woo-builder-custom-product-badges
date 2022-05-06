'use strict';

let JWBCPBSettingsMixin = {
	data: function() {
		return {
			pageOptions: window.JWBCPBSettingsConfig.settingsData,
			inputBadge: '',
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
		};
	},

	created: function() {
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				for ( let option in options ) {
					if ( options.hasOwnProperty( option ) ) {
						prepared[ option ] = Array.isArray( options[option] ) ? options[option] : options[option]['value'];
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
					alert( 'This badge already exist!' );
				}

			}
		},

		deleteBadge: function ( value, label ) {
			let confirmDeletion = confirm('Are you sure you want to delete ` ' + label + '` badge?');

			if ( confirmDeletion ) {
				this.pageOptions.badgesList = this.pageOptions.badgesList.filter( badge => badge.value !== value );
				this.pageOptions.actionType['value'] = 'remove';
			}
		},

		saveOptions: function() {
			let self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				url: window.JWBCPBSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( response, textStatus, jqXHR ) {
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
	}
}

Vue.component( 'jwb-custom-product-badges-general-settings', {
	template: '#jet-dashboard-jwb-custom-product-badges-general-settings',
	mixins: [ JWBCPBSettingsMixin ],
} );
