<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php echo $content_top; ?>

		<h1 class="news_title a_left"><?php echo $heading_title; ?></h1>
		<div class="content">
			<?php if ($staff_info['thumb']) { ?>
				<a href="<?php echo $staff_info['popup']; ?>" title="<?php echo $heading_title; ?>" class="fancybox"><img align="right" style="border: none; margin-left: 10px;" src="<?php echo $staff_info['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
			<?php } ?>
			<?php echo $staff_info['descr']; ?>
			<?php // echo $this->getChild('common/comments', array("product_id"=>$news_id) ); ?>
		</div>

  <?php echo $content_bottom; ?>
</div>
<script>
$('.fancybox').fancybox();
</script>
<?php echo $footer; ?>
