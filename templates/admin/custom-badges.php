<div class="jet-woo-builder-settings-page jet-woo-builder-settings-page__custom_badges">
	<div class="jwb-custom-product-badges">
		<div class="jwb-custom-product-badges__head">
			<h3 class="cx-vui-subtitle">
				<?php _e( 'Badges List', 'jwb-custom-product-badges' ); ?>
			</h3>

			<form class="jwb-custom-badges-add-form" @submit.prevent="addNewBadge">
				<div class="cx-vui-component__control">
					<input type="text" v-model.trim="inputBadge"/>
					<button type="submit">+ Add New</button>
				</div>
				<div class="cx-vui-component__desc">To add multiple badges at once, separate the line with commas.</div>
			</form>
		</div>

		<div class="jwb-custom-product-badges__body">
			<ul class="jwb-custom-product-badges-list">
				<li class="jwb-custom-product-badges-list-item" v-for="( badge, index ) in pageOptions.badgesList">
					{{ badge.label }}

					<div class="jwb-custom-product-badges-list-item__actions">
						<button class="jwb-custom-product-badges-list-item__remove" @click="confirmDeleteBadge( badge )">
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

						<div class="cx-vui-tooltip" v-if="deleteBadgeTrigger === badge">
							<?php _e( 'Are you sure?', 'jwb-custom-product-badges' ); ?>
							<br>

							<span class="cx-vui-repeater-item__confrim-del" @click="deleteBadge( badge.value)">
								<?php _e( 'Yes', 'jwb-custom-product-badges' ); ?>
							</span>
							/
							<span class="cx-vui-repeater-item__cancel-del" @click="deleteBadgeTrigger = null">
								<?php _e( 'No', 'jwb-custom-product-badges' ); ?>
							</span>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>