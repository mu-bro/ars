<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">

	<h1 class="news_title a_left"><?php echo $heading_title; ?></h1>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>  
  <?php echo $content_top; ?>
  
	<?php if (isset($news_info)) { ?>
			<div class="content news_small_descr">
				<?php if ($image) { ?>
					<div class="news_image" style="margin-bottom:10px;">
						<img src="<?php echo $image; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
						<div class="news_date">
							<span><?php echo $date[0]; ?></span>
							<span><?php echo $date[1] . "<br/>" . $date[2]; ?></span>
						</div>
					</div>
				<?php } ?>
					<h2 class="news_title"><?php echo $heading_title; ?></h2>
					<div class="news_descr">
						<?php echo $description; ?>
					</div>
			</div>

			<div class="clear"></div>
			<a href="<?php echo $news; ?>" class="more_info bordered">
				<i class="fa fa-chevron-left"></i>
				<?php echo $text_back_to_news; ?>
			</a>
	
			
	<?php } elseif (isset($news_data)) { ?>		
		<?php foreach ($news_data as $news) { ?>
			<div class="news_small_descr">
				<?php if ($news['image']) { ?>
					<div class="news_image">
						<a href="<?php echo $news['href']; ?>" title="<?php echo $news['title']; ?>"><img src="<?php echo $news['image']; ?>" title="<?php echo $news['title']; ?>" alt="<?php echo $news['title']; ?>" /></a>
						<div class="news_date">
							<span><?php echo $news['date_added_formated'][0]; ?></span>
							<span><?php echo $news['date_added_formated'][1] . "<br/>" . $news['date_added_formated'][2]; ?></span>
						</div>
					</div>
				<?php } ?>
				<div class="news_info">
					<h2 class="news_title"><?php echo $news['title']; ?></h2>
					<div class="news_descr">
						<?php echo $news['description']; ?>
					</div>
					<a href="<?php echo $news['href']; ?>" class="more_info">
						<?php echo $text_read_more; ?> 
						<i class="fa fa-chevron-right"></i>
					</a>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>
	<?php } ?>
  <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
