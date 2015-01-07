    <?php $i = 0; foreach ($products as $product) { $i++; ?>
    <div class="<?php echo ($i % 4 == 0) ? "noBorder" : "" ; ?>">
		<div class="jumper">
			<div class="manufacturer"><a href="<?php echo $product['manufacturer_link']; ?>"><?php echo $product['manufacturer']; ?></a></div>
			<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
			<?php if ($product['thumb']) { ?>
				<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
			<?php } ?>
			<div class="add_info">
				<div class="model"><span><?php echo $text_model; ?></span> <?php echo $product['model']; ?></div>
				<div class="stock <?php echo ($product['quantity'] > 0) ? "green" : "red" ; ?>"><?php echo ($product['quantity'] > 0) ? $text_in_stock : $text_out_stock ; ?></div>
			</div>
			<div class="parametrs animated fadeIn">
				 <?php foreach ($product['attributes'] as $attribute_group) { ?>
					<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
						<?php echo $attribute['name']; ?>: <?php echo $attribute['text']; ?><br/>
					<?php } ?>
				<?php } ?>
			</div>
			<?php if ($product['price']) { ?>
				<div class="price">
					<?php if (!$product['special']) { ?>
					<?php echo $product['price']; ?>
					<?php } else { ?>
					<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
					<?php } ?>
					<?php if ($product['tax']) { ?>
					<br />
					<span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
					<?php } ?>
				</div>
			<?php } ?>
			<?php if ($product['rating']) { ?>
				<div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
			<?php } ?>
			<div class="cart">
				<a class="button" href="<?php echo $product['href']; ?>" ><?php echo $text_more_info; ?></a>
			</div>
		</div>
    </div>
<?php } ?>

<script>
	$('.product-grid > div').hover(function(){
		$(this).find(".jumper").toggleClass('animated pulse');
	});	
</script>