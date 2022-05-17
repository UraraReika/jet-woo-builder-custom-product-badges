<div class="jwb-custom-product-badges-settings-page jwb-custom-product-badges-settings-page__general">
	<h3 class="cx-vui-subtitle"><?php _e( 'Badges List', 'jet-woo-builder' ); ?></h3>

	<ul class="jwb-custom-product-badges-list">
		<li class="jwb-custom-product-badges-list__item" v-for="(badge, index) in pageOptions.badgesList">
			{{ badge.label }}
			<button class="jwb-custom-product-badges-list__item-remove" @click="deleteBadge( badge.value, badge.label )">
				<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 371.23 371.23" style="enable-background:new 0 0 371.23 371.23;" xml:space="preserve">
					<polygon points="371.23,21.213 350.018,0 185.615,164.402 21.213,0 0,21.213 164.402,185.615 0,350.018 21.213,371.23 185.615,206.828 350.018,371.23 371.23,350.018 206.828,185.615 "/>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
					<g></g>
				</svg>
			</button>
		</li>
	</ul>

	<h3 class="cx-vui-subtitle"><?php _e( 'Add New Badges', 'jet-woo-builder' ); ?></h3>

	<form @submit.prevent="addNewBadge">
		<input type="text" v-model.trim="inputBadge">
		<button type="submit">Add New</button>
	</form>
</div>