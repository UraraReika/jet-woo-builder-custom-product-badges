<div class="jwb-custom-product-badges-settings-page jwb-custom-product-badges-settings-page__general">
	<h3 class="cx-vui-subtitle"><?php _e( 'Badges List', 'jet-woo-builder' ); ?></h3>

	<div v-for="(badge, index) in pageOptions.badgesList">
		{{ badge.label }}
	</div>
</div>