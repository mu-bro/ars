
<div class="box">
	<div class="box-heading news"><a href="<?php echo $this->url->link("information/" . $module_type); ?>"><?php echo $heading_title; ?></a></div>
	<div class="box-content <?php echo $setting['position']; ?>">
		<?php if (isset($news_info)) { ?>
			<div class="review_block small_block" >
				<div class="content" <?php if ($image) { echo 'style="min-height: ' . $min_height . 'px;"'; } ?>>
					<?php if ($image) { ?>
						<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="fancybox"><img align="right" style="border: none; margin-left: 10px;" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
					<?php } ?>
					<?php echo $description; ?>
				</div>
				<div class="buttons">
				<div class="right"><a onclick="location='<?php echo $news; ?>'" class="button"><span><?php echo $button_news; ?></span></a></div>
				</div>
			</div>
		<?php } elseif (isset($news_data)) { ?>
			<ul class="bxslider_<?php echo $module_type . "_" . $module; echo " " . $setting['position']; ?> bxSliderUl" >
				<?php foreach ($news_data as $i => $news) { $k = $i % 4; ?>
					<li class="manufacturer-list" >
						<div class="manufacturer-image">
							<a href="<?php echo $news['href']; ?>">
								<img src="<?php echo $news['image']; ?>" title="<?php echo $news['title']; ?>">
							</a>
						</div>
						<div class="manufacturer-right">
							<div class="ews_date_list type_<?php echo $k; ?> manufacturer-heading">
								<a href="<?php echo $news['href']; ?>" class="news_name">
									<?php echo $news['title']; ?>
								</a>
							</div>
							<div class="manufacturer-date">
								<?php echo $news['date_added'][0] . " " . $news['date_added'][1] . " " . $news['date_added'][2]; ?>
							</div>
							<div class="manufacturer-content">
								<?php echo $news['short_descr']; ?>
							</div>
						</div>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
</div>
<?php if (in_array($setting['position'],array("content_top","content_bottom"))) { ?>
	<script type="text/javascript">
	$('.bxslider_<?php echo $module_type . "_" . $module; ?>').bxSlider({
	minSlides: 2,
	maxSlides: 2,
	slideWidth: 466,
	pager:true,
	controls:false,
	auto:true,
	slideMargin: 10
	});
	$(document).ready(function() {
		$('.bxslider_<?php echo $module_type . "_" . $module; ?>').parent().attr("id","bxslider_<?php echo $module_type . "_" . $module; ?>_wrap");
	});	
	</script>
	<style>
		.bxslider_<?php echo $module_type . "_" . $module; ?> {
			height:103px;
		}
		#bxslider_<?php echo $module_type . "_" . $module; ?>_wrap {
			height:105px!important;
		}
	</style>
<?php } ?>