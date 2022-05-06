<div class="jwb-custom-product-badges-settings-page jwb-custom-product-badges-settings-page__general">
	<h3 class="cx-vui-subtitle"><?php _e( 'Badges List', 'jet-woo-builder' ); ?></h3>

	<div v-for="(badge, index) in pageOptions.badgesList">
		{{ badge.label }}
	</div>

	<form @submit.prevent="addNewBadge">
		<input type="text" v-model.trim="inputBadge">
		<button type="submit">Add new</button>
	</form>
</div>