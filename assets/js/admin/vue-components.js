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
	//	console.log(this.pageOptions);
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				for ( let option in options ) {

					if ( options.hasOwnProperty( option ) ) {
						if ( options[option].hasOwnProperty('value') ) {
							prepared[ option ] = options[option]['value'];
						} else {
							prepared[ option ] = options[option];
						}
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
				this.pageOptions.badgesList.push( {
					'value': this.inputBadge.toLowerCase().replace( /\s/g, '-' ),
					'label': this.inputBadge
				} );

				this.inputBadge = '';
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
