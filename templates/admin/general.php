<div class="jet-woo-builder-settings-page jet-woo-builder-settings-page__custom_badges">
	<div class="jwb-custom-product-badges">
		<div class="jwb-custom-product-badges__head">
			<h3 class="cx-vui-subtitle">
				<?php _e( 'Badges List', 'jwb-custom-product-badges' ); ?>
			</h3>

			<form class="jwb-custom-badges-add-form" @submit.prevent="addNewBadge">
				<input type="text" v-model.trim="inputBadge"/>
				<button type="submit">+ Add New</button>
			</form>
		</div>

		<div class="jwb-custom-product-badges__body">
			<ul class="jwb-custom-product-badges-list">
				<li class="jwb-custom-product-badges-list-item" v-for="( badge, index ) in pageOptions.badgesList">
					{{ badge.label }}
					<button class="jwb-custom-product-badges-list-item__remove" @click="deleteBadge( badge.value, badge.label )">
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
		</div>
	</div>
</div>