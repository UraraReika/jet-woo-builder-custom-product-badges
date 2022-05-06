'use strict';

let JWBCPBSettingsMixin = {
	data: function() {
		return {
			pageOptions: window.JWBCPBSettingsConfig.settingsData,
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
						prepared[ option ] = options[option]['value'];
					}
				}

				this.preparedOptions = prepared;

				this.saveOptions();
			},
			deep: true
		}
	},

	methods: {
		saveOptions: function() {
			let self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				url: window.jetWooBuilderSettingsConfig.settingsApiUrl,
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
