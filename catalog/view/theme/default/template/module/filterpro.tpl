<?php if($options || $manufacturers || $attributes || $price_slider) { ?>
<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
		<div style="height: 15px"><a class="clear_filter"><?php echo $clear_filter?></a></div>
		<form id="filterpro">
			<?php if($manufacturers) { ?>
			<?php foreach($manufacturers as $manufacturer) { ?>
				<input type="hidden" class="m_name" id="m_<?php echo $manufacturer['manufacturer_id']?>" value="<?php echo $manufacturer['name']?>">
				<?php } ?>
			<?php } ?>

			<?php if($options) { ?>
			<?php foreach($options as $option) { ?>
				<?php foreach($option['option_values'] as $option_value) { ?>
					<input type="hidden" class="o_name" id="o_<?php echo $option_value['option_value_id']?>" value="<?php echo $option_value['name']?>">
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<input type="hidden" name="category_id" value="<?php echo $category_id ?>">
			<input type="hidden" name="page" id="filterpro_page" value="0">
			<input type="hidden" name="path" value="<?php echo $path ?>">
			<input type="hidden" name="sort" id="filterpro_sort" value="">
			<input type="hidden" name="order" id="filterpro_order" value="">
			<input type="hidden" name="limit" id="filterpro_limit" value="">

			<div class="price_slider" <?php if(!$price_slider) {echo 'style="display:none"';}?>>
				<label for="min_price"><?php echo $text_price_range?></label><br/>
				<table>
					<tr>
						<td><input class="price_limit" type="text" name="min_price" value="-1" id="min_price"/></td>
						<td><input class="price_limit" type="text" name="max_price" value="-1" id="max_price"/></td>
					</tr>
				</table>

			<div id="slider-range"></div>

			</div>
			<?php if($manufacturers) { ?>
			<div class="option_box">
				<div class="option_name"><?php echo $text_manufacturers; ?></div>
				<?php if($display_manufacturer == 'select') { ?>
				<div class="collapsible">
					<select name="manufacturer[]" class="filtered">
						<option value=""><?php echo $text_all?></option>
						<?php foreach($manufacturers as $manufacturer) { ?>
						<option id="manufacturer_<?php echo $manufacturer['manufacturer_id']?>" class="manufacturer_value" value="<?php echo $manufacturer['manufacturer_id']?>"><?php echo $manufacturer['name']?></option>
						<?php } ?>
					</select>
				</div>
				<?php } elseif($display_manufacturer == 'checkbox') { ?>
				<table class="collapsible">
					<?php foreach($manufacturers as $manufacturer) { ?>
					<tr>
						<td>
							<input id="manufacturer_<?php echo $manufacturer['manufacturer_id']?>" class="manufacturer_value filtered"
								   type="checkbox" name="manufacturer[]"
								   value="<?php echo $manufacturer['manufacturer_id']?>">
						</td>
						<td>
							<label for="manufacturer_<?php echo $manufacturer['manufacturer_id']?>"><?php echo $manufacturer['name']?></label>
						</td>
					</tr>
					<?php } ?>
				</table>
				<?php } elseif($display_manufacturer == 'radio') { ?>
				<table class="collapsible">
					<?php foreach($manufacturers as $manufacturer) { ?>
					<tr>
						<td>
							<input id="manufacturer_<?php echo $manufacturer['manufacturer_id']?>" class="manufacturer_value filtered"
								   type="radio" name="manufacturer[]"
								   value="<?php echo $manufacturer['manufacturer_id']?>">
						</td>
						<td>
							<label for="manufacturer_<?php echo $manufacturer['manufacturer_id']?>"><?php echo $manufacturer['name']?></label>
						</td>
					</tr>
					<?php } ?>
				</table>
				<?php }?>
			</div>
			<?php } ?>

			<?php if($attributes) { ?>
			<?php foreach($attributes as $attribute_group_id => $attribute) { ?>
			<div class="option_box">
				<div class="attribute_group_name"><?php echo $attribute['name']; ?></div>
				<?php foreach($attribute['attribute_values'] as $attribute_value_id => $attribute_value) { ?>
				<div class="attribute_box">
					<div class="option_name"><?php echo $attribute_value['name']; ?></div>
					<?php if($attribute_value['display'] == 'select') { ?>
					<div class="collapsible">
						<select class="filtered" name="attribute_value[<?php echo $attribute_value_id?>][]">
							<option value=""><?php echo $text_all?></option>
							<?php foreach($attribute_value['values'] as $i => $value) { ?>
							<option class="a_name"
									at_v_i="<?php echo $attribute_value_id . '_' . $value ?>"
									at_v_t="<?php echo $attribute_value_id . '_' . $value ?>"
									data-value = "<?php echo $value ?>"
									value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php }?>
						</select>
					</div>
					<?php } elseif($attribute_value['display'] == 'checkbox') {?>
					<table class="collapsible">
						<?php foreach($attribute_value['values'] as $i => $value) { ?>
						<tr>
							<td>
								<input class="filtered a_name"
									   id="attribute_value_<?php echo $attribute_value_id . $i; ?>"
									   type="checkbox" name="attribute_value[<?php echo $attribute_value_id?>][]"
									   at_v_i="<?php echo $attribute_value_id . '_' . $value ?>"
									   value="<?php echo $value ?>">
							</td>
							<td>
								<label for="attribute_value_<?php echo $attribute_value_id . $i; ?>"
									   at_v_t="<?php echo $attribute_value_id . '_' . $value ?>"
									   data-value = "<?php echo $value ?>"
									   value="<?php echo $value ?>"><?php echo $value?></label>
							</td>
						</tr>
						<?php } ?>
					</table>
					<?php } elseif($attribute_value['display'] == 'radio') {?>
					<table class="collapsible">
						<?php foreach($attribute_value['values'] as $i => $value) { ?>
						<tr>
							<td>
								<input class="filtered a_name"
									   id="attribute_value_<?php echo $attribute_value_id . $i; ?>"
									   type="radio" name="attribute_value[<?php echo $attribute_value_id?>][]"
									   at_v_i="<?php echo $attribute_value_id . '_' . $value ?>"
									   value="<?php echo $value ?>">
							</td>
							<td>
								<label for="attribute_value_<?php echo $attribute_value_id . $i; ?>"
										at_v_t="<?php echo $attribute_value_id . '_' . $value ?>"
										data-value = "<?php echo $value ?>"
										value="<?php echo $value ?>"><?php echo $value?></label>
							</td>
						</tr>
						<?php } ?>
					</table>
					<?php }?>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
			<?php } ?>

			<?php if($options) { ?>
			<?php foreach($options as $option) { ?>
			<div class="option_box">
				<div class="option_name"><?php echo $option['name']; ?></div>
				<?php if($option['display'] == 'select') { ?>
				<div class="collapsible">
					<select class="filtered" name="option_value[<?php echo $option['option_id']?>][]">
						<option value=""><?php echo $text_all?></option>
						<?php foreach($option['option_values'] as $option_value) { ?>
						<option class="option_value" id="option_value_<?php echo $option_value['option_value_id']?>" value="<?php echo $option_value['option_value_id'] ?>"><?php echo $option_value['name']?></option>
						<?php }?>
					</select>
				</div>
				<?php } elseif($option['display'] == 'checkbox') {?>
					<table class="collapsible">
						<?php foreach($option['option_values'] as $option_value) { ?>
						<tr>
							<td>
								<input class="filtered option_value" id="option_value_<?php echo $option_value['option_value_id']?>"
									   type="checkbox" name="option_value[<?php echo $option['option_id']?>][]"
									   value="<?php echo $option_value['option_value_id']?>">
							</td>
							<td>
								<label for="option_value_<?php echo $option_value['option_value_id']?>"><?php echo $option_value['name']?></label>
							</td>
						</tr>
						<?php } ?>
					</table>
				<?php } elseif($option['display'] == 'radio') {?>
				<table class="collapsible">
					<?php foreach($option['option_values'] as $option_value) { ?>
					<tr>
						<td>
							<input class="filtered option_value" id="option_value_<?php echo $option_value['option_value_id']?>"
								   type="radio" name="option_value[<?php echo $option['option_id']?>][]"
								   value="<?php echo $option_value['option_value_id']?>">
						</td>
						<td>
							<label for="option_value_<?php echo $option_value['option_value_id']?>"><?php echo $option_value['name']?></label>
						</td>
					</tr>
					<?php } ?>
				</table>
				<?php }?>
			</div>
			<?php } ?>
			<?php } ?>
		</form>
	</div>


<script id="productTemplate" type="text/x-jquery-tmpl">
	<div>
		{{if thumb}}
			<div class="image"><a href="${href}"><img src="${thumb}" title="${name}" alt="${name}"/></a></div>
		{{/if}}
		<div class="name"><a href="${href}">${name}</a>
			<div class="extra" style="color: #444;">
				{{if sku}}
				<span style="color:#38b0e3"><?php echo $pds_sku; ?></span> ${sku} <br/>
				{{/if}}
				{{if model}}
				<span style="color:#38b0e3"><?php echo $pds_model; ?></span> ${model} <br/>
				{{/if}}
				{{if brand}}
				<span style="color:#38b0e3"><?php echo $pds_brand; ?></span> ${brand} <br/>
				{{/if}}
				{{if location}}
				<span style="color:#38b0e3"><?php echo $pds_location; ?></span> ${location} <br/>
				{{/if}}
				{{if upc}}
				<span style="color:#38b0e3"><?php echo $pds_upc; ?></span> ${upc} <br/>
				{{/if}}
				{{if stock}}
				<span style="color:#38b0e3"><?php echo $pds_stock; ?></span> ${stock} <br/>
				{{/if}}
			</div>
		</div>
		<div class="description">${description}</div>
		{{if price}}
			<div class="price">
				{{if special }}
					<span class="price-old">${price}</span> <span class="price-new">${special}</span>
				{{else}}
					${price}
				{{/if}}
				{{if tax}}
					<br/>
					<span class="price-tax"><?php echo $text_tax; ?> ${tax}</span>
				{{/if}}
			</div>
		{{/if}}
		{{if rating}}
			<div class="rating"><img src="catalog/view/theme/default/image/stars-${rating}.png" alt="${reviews}" /></div>
		{{/if}}
		<div class="cart"><a onclick="addToCart('${product_id}');" class="button"><span><?php echo $button_cart; ?></span></a></div>
		<div class="wishlist"><a onclick="addToWishList('${product_id}');"><?php echo $button_wishlist; ?></a></div>
		<div class="compare"><a onclick="addToCompare('${product_id}');"><?php echo $button_compare; ?></a></div>
	</div>
</script>
</div>

<?php } ?>