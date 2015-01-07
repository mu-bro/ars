<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>  
  <?php echo $content_top; ?>
  
	<?php if (isset($news_info)) { ?>
		<h1 class="news_title a_left"><span class="title_date"><?php echo $date_added; ?></span><?php echo $heading_title; ?></h1>
		<div class="content" <?php if ($image) { echo 'style="min-height: ' . $min_height . 'px;"'; } ?>>
			<?php if ($image) { ?>
				<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="fancybox"><img align="right" style="border: none; margin-left: 10px;" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
			<?php } ?>
			<?php echo $description; ?>
			<?php echo $this->getChild('common/comments', array("product_id"=>$news_id) ); ?>
		</div>
	<?php } elseif (isset($news_data)) { ?>
		<h1 class="news_title a_left"><?php echo $heading_title; ?></h1>
		<?php foreach ($news_data as $news) { ?>
			<div class="content news_small_descr">
					<h2 class="news_title"><?php echo $news['title']; ?></h2>
					<?php echo $news['description']; ?>
					<div class="news_date"><?php echo $news['date_added']; ?></div>
					<a href="<?php echo $news['href']; ?>" class="more_info button"><?php echo $text_read_more; ?></a></p>
			</div>
			<br/>
		<?php } ?>
	<?php } ?>
  <?php echo $content_bottom; ?>
</div>
<script>
$('.fancybox').fancybox();
</script>
<?php echo $footer; ?>
