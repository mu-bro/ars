
<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">

			<div class="review_block small_block" >
				<?php if (isset($news_info)) { ?>
				<div class="content" <?php if ($image) { echo 'style="min-height: ' . $min_height . 'px;"'; } ?>>
					<?php if ($image) { ?>
						<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="fancybox"><img align="right" style="border: none; margin-left: 10px;" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
					<?php } ?>
					<?php echo $description; ?>
				</div>
				<div class="buttons">
				<div class="right"><a onclick="location='<?php echo $news; ?>'" class="button"><span><?php echo $button_news; ?></span></a></div>
				</div>
				<?php } elseif (isset($news_data)) { ?>
					<?php foreach ($news_data as $i => $news) { $k = $i % 4; ?>
						<div class="manufacturer-list" >
							<div class="ews_date_list type_<?php echo $k; ?> manufacturer-heading">
								<?php echo $news['date_added_plane']; ?>
							</div>
							<div class="manufacturer-content">
								<a href="<?php echo $news['href']; ?>" class="news_name">
									<?php echo $news['title']; ?>
								</a>							
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>

	</div>
</div>