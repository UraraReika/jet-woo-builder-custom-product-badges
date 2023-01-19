<div class="jet-woo-builder-settings-page jet-woo-builder-settings-page__custom_badges">
	<div class="jwb-custom-product-badges">
		<div class="jwb-custom-product-badges__head">
			<h3 class="cx-vui-subtitle">
				<?php _e( 'Badges List', 'jwb-custom-product-badges' ); ?>
			</h3>

			<form class="jwb-custom-badges-add-form" @submit.prevent="addNewBadge">
				<div class="cx-vui-component__control">
					<input type="text" v-model.trim="inputBadge"/>
					<button type="submit"><?php _e( '+ Add New', 'jwb-custom-product-badges' ); ?></button>
				</div>

				<div class="cx-vui-component__desc">
					<?php _e( 'To add multiple badges at once, separate the line with commas.', 'jwb-custom-product-badges' ); ?>
				</div>
			</form>
		</div>

		<div class="jwb-custom-product-badges__body" v-if="pageOptions.badgesList.length > 0">
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

			<h3 class="cx-vui-subtitle">
				<?php _e( 'Display Conditions', 'jwb-custom-product-badges' ); ?>
			</h3>

			<div class="cx-vui-inner-panel query-panel">
				<cx-vui-repeater
					button-label="<?php _e( 'Add New Display Condition', 'jwb-custom-product-badges' ); ?>"
					button-style="accent"
					button-size="mini"
					v-model="pageOptions.displayConditions"
					@add-new-item="addNewField( { 'badges': [], 'parameter': 'products', 'operator': 'equal', 'value': [], 'collapsed': false } )"
				>
					<cx-vui-repeater-item
						v-for="( condition, index ) in pageOptions.displayConditions"
						:title="'Badges: ' + pageOptions.displayConditions[ index ].badges"
						:subtitle="'Condition: ' + pageOptions.displayConditions[ index ].parameter + ' ' + pageOptions.displayConditions[ index ].operator + ' to ' + pageOptions.displayConditions[ index ].value"
						:collapsed="isCollapsed( condition )"
						:index="index"
						@clone-item="cloneField( $event )"
						@delete-item="deleteField( $event )"
						:key="condition._id"
					>
						<cx-vui-f-select
							label="<?php _e( 'Badges', 'jwb-custom-product-badges' ); ?>"
							description="<?php _e( 'Select badges for specific display condition.', 'jwb-custom-product-badges' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:options-list="pageOptions.badgesList"
							:multiple="true"
							:value="pageOptions.displayConditions[ index ].badges"
							@input="setFieldProp( index, 'badges', $event )"
						></cx-vui-f-select>

						<cx-vui-select
							label="<?php _e( 'Parameter', 'jet-custom-product-badges' ); ?>"
							description="<?php _e( 'Select parameter of display condition.', 'jet-custom-product-badges' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:options-list="[
										{
											value: 'products',
											label: '<?php _e( 'Products', 'jwb-custom-product-badges' ); ?>',
										},
									]"
							size="fullwidth"
							:value="pageOptions.displayConditions[ index ].parameter"
							@input="setFieldProp( index, 'parameter', $event )"
						></cx-vui-select>

						<cx-vui-select
							label="<?php _e( 'Operator', 'jet-custom-product-badges' ); ?>"
							description="<?php _e( 'Select operator of display condition.', 'jet-custom-product-badges' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:options-list="[
										{
											value: 'equal',
											label: '<?php _e( 'Equal to', 'jwb-custom-product-badges' ); ?>',
										},
										{
											value: 'not_equal',
											label: '<?php _e( 'Not equal to', 'jwb-custom-product-badges' ); ?>',
										}
									]"
							size="fullwidth"
							:value="pageOptions.displayConditions[ index ].operator"
							@input="setFieldProp( index, 'operator', $event )"
						></cx-vui-select>

						<cx-vui-f-select
							v-if="'products' === pageOptions.displayConditions[ index ].parameter"
							label="<?php _e( 'Products', 'jwb-custom-product-badges' ); ?>"
							description="<?php _e( 'Select products for specific display condition.', 'jwb-custom-product-badges' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							:options-list="conditionsOptions.productsList"
							:multiple="true"
							:value="pageOptions.displayConditions[ index ].value"
							@input="setFieldProp( index, 'value', $event )"
						></cx-vui-f-select>
					</cx-vui-repeater-item>
				</cx-vui-repeater>
			</div>
		</div>
	</div>
</div>