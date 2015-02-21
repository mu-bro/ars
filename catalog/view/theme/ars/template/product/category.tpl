<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <h1 class="box-heading"><?php echo $heading_title; ?></h1>
  <div class="breadcrumb noMargin">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>  
  <?php if ($products) { ?>
	<div class="product-filter">
		<div class="pagination"><?php echo $pagination; ?></div>
		<div class="limit"><b><?php echo $text_limit; ?></b>
		<select onchange="location = this.value;" class="custome">
			<?php foreach ($limits as $limits) { ?>
			<?php if ($limits['value'] == $limit) { ?>
			<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
			<?php } ?>
			<?php } ?>
		</select>
		</div>
		<div class="sort"><b><?php echo $text_sort; ?></b>
		<select onchange="location = this.value;" class="custome">
			<?php foreach ($sorts as $sorts) { ?>
			<?php if ($sorts['value'] == $sort . '-' . $order) { ?>
			<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
			<?php } ?>
			<?php } ?>
		</select>
		</div>
	</div>
	<div class="product-grid">
		<br/><img src="catalog/view/theme/ars/image/loading.gif"> Загрузка товаров...<br/><br/>
		<?php // include 'catalog/view/theme/ars/template/product/product_list.tpl'; ?>
	</div>
	<div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php if (!$categories && !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
	<?php if ($description) { ?>
		<div class="category-info">
			<?php if ($description) { ?>
				<?php echo $description; ?>
			<?php } ?>
		</div>
	<?php } ?>  
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>