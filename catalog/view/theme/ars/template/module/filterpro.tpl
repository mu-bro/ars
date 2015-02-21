<?php if($options || $manufacturers || $attributes || $price_slider) { ?>
<div class="box">
	<div class="box-heading orange"><?php echo $heading_title; ?></div>
	<div class="box-content">
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
				<label for="min_price"><?php echo $text_price_range?></label>
				<div id="slider-range"></div>
				<table>
					<tr>
						<td>От <input class="price_limit" type="text" name="min_price" value="-1" id="min_price"/></td>
						<td style="text-align:right;"> до<input class="price_limit" type="text" name="max_price" value="-1" id="max_price"/> р.</td>
					</tr>
				</table>		
			</div>
			<?php if($manufacturers) { ?>
			<div class="option_box" <?php if (isset($this->request->get['manufacturer_id'])) echo "style='display:none;'"; ?>>
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
								   value="<?php echo $manufacturer['manufacturer_id']?>" <?php if (isset($this->request->get['manufacturer_id']) && $manufacturer['manufacturer_id'] == $this->request->get['manufacturer_id']) echo "checked='checked'"; ?>>
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
					<?php } elseif($attribute_value['display'] == 'slide') {?>
					<div class="price_slider" >
						<div id="slider-range-<?php echo $attribute_value_id; ?>"></div>
						<table id="table_<?php echo $attribute_value_id; ?>">
							<tr>
								<td>
									<input rel="<?php echo $attribute_value_id; ?>" class="filtered showSlider"
										type="checkbox" name="attribute_value[<?php echo $attribute_value_id?>][show]"
										value="1" onchange="switchSlider($(this));" style="margin-top:7px;">
								</td>
								<td>
									От <input id="min_price-<?php echo $attribute_value_id; ?>" class="price_limit filtered_input" type="text" disabled="disabled" name="attribute_value[<?php echo $attribute_value_id?>][min]" value="<?php echo $attribute_value['min']; ?>" rel="<?php echo $attribute_value['min']; ?>" />
								</td>
								<td style="text-align:right;">
									до<input id="max_price-<?php echo $attribute_value_id; ?>" class="price_limit filtered_input" type="text" disabled="disabled" name="attribute_value[<?php echo $attribute_value_id?>][max]" value="<?php echo $attribute_value['max']; ?>" rel="<?php echo $attribute_value['max']; ?>" />
								</td>
							</tr>
						</table>
					</div>
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
			<div class="price_slider" style="display:none;">
				<label for="product_id_input"><?php echo $text_by_product_id; ?></label>
				<table>
					<tr>
						<td style="text-align:right;"># <input style="margin:0px;width:140px;" type="text" class="price_limit filtered_input" name="product_id" id="product_id_input"/></td>
					</tr>
				</table>
			</div>
			<?php if($options) { ?>
			<?php foreach($options as $option) { if($option['display'] == 'color' && $this->request->get['path'] == 100) continue; ?>
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
				<?php } elseif($option['display'] == 'color') { ?>
				<table class="color_block collapsible">
					<?php foreach($option['option_values'] as $option_value) { ?>
					<tr>
						<td>
							<input class="filtered option_value" id="option_value_<?php echo $option_value['option_value_id']?>"
											type="radio" name="option_value[<?php echo $option['option_id']?>][]"
									   value="<?php echo $option_value['option_value_id']?>">
					
						</td>					
						<td>
							<label class="f_color"  for="option_value_<?php echo $option_value['option_value_id']?>" >
								<div style="background-color:<?php echo $option_value['color']?>;"></div>
								<span><?php echo $option_value['name']?></span>
							</label>
						</td>

					</tr>					
					<?php } ?>
				</table>
				<div style="display:none;" id="color_block_hidden">
					<table>
					<tr>
					<?php foreach($option['option_values'] as $option_value) { ?>
						<td>
							<label class="f_color"  for="option_value_<?php echo $option_value['option_value_id']?>" >
								<div style="background-color:<?php echo $option_value['color']?>;"  onclick="if ( !$(this).hasClass('active') ) $(this).addClass('active');" id="hidden_color_<?php echo $option_value['option_value_id']?>" class="hidden_color">&nbsp;</div>
							</label>
						</td>
					<?php } ?>
						<td class="all_color_link_td active">
							<a class="all_color_link" onclick="func_clear_color_filter();">Все цвета</a>
						</td>
					</tr>
					</table>
				</div>
				<script type="text/javascript">
					$('#filterpro .color_block input').bind('change',function() {
						input_id = $(this).attr('id');
						option_id = $(this).val();
						$('.f_color').removeClass('active');
						$('.hidden_color').removeClass('active');
						$('.all_color_link_td').removeClass('active');

						if ( $(this).parent().parent().find('label[for="'+input_id+'"]').hasClass('active') ) {
							$(this).parent().parent().find('label[for="'+input_id+'"]').removeClass('active');
							$('#hidden_color_' + option_id).removeClass('active');
						} else {
							$(this).parent().parent().find('label[for="'+input_id+'"]').addClass('active');
							$('#hidden_color_' + option_id).addClass('active');
						}
					});
					function func_clear_color_filter() {
						$('.all_color_link_td').addClass('active');
						$('.f_color').removeClass('active');
						$('.hidden_color').removeClass('active');
						$('.color_block input[type="radio"]').removeAttr('checked');
						doFilter(false);
					}
					$(document).ready(function() {
						$('#top_filter').html( $('#color_block_hidden').html() );
						$('.product_top_filte_holder').show();
						$('#color_block_hidden').html('');
						$('#filterpro .color_block input').each(function() {
							option_id = $(this).val();
							if ( $(this).is(':checked') ) {
								$('#hidden_color_' + option_id).addClass('active');
								$('.all_color_link_td').removeClass('active');
							}
						});
					});
				</script>
				<?php }?>
				
				
			</div>
			<?php } ?>
			<?php } ?>
		</form>
		<div style="height: 15px"><a class="clear_filter"><?php echo $clear_filter?></a></div>		
	</div>
</div>

<?php } ?>