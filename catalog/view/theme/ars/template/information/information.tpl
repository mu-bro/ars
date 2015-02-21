<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<h1><?php echo $heading_title; ?></h1>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="information_block">
    <?php echo $description; ?>
    <div class="clear" style="height:20px;"></div>
  </div>

  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>