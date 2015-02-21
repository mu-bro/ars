<?php if ($products) { ?>
  <div class="box">
    <div class="box-heading"><?php echo $heading_title; ?></div>
    <div class="box-content">
      <div class="product-grid">
        <?php include 'catalog/view/theme/ars/template/product/product_list.tpl'; ?>
      </div>
      <div class="clear"></div>
    </div>
  </div>
<?php } ?>