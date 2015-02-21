<div id="banner<?php echo $module; ?>" class="camera_wrap"  >
	<?php foreach ($banners as $banner) { ?>	
			<div data-src="<?php echo $banner['image']; ?>" <?php if ($banner['link']) { ?> data-link="<?php echo $banner['link']; ?>" <?php } ?>>
                <div class="caption fadeIn">
					<div class="container">
						<div class="row">
							<div class="grid_12">
								<h6><?php echo str_replace("\r","<br/>",$banner['title']); ?></h6>
<!--								--><?php //if ($banner['link']) { ?>
<!--									<a href="--><?php //echo $banner['link']; ?><!--" class="button">--><?php //echo $text_more_info; ?><!--</a>-->
<!--								--><?php //} ?>
							</div>
						</div>
					</div>
                </div>
			</div>
	<?php } ?>
</div>
<script type='text/javascript' >
$('#banner<?php echo $module; ?>').camera({
	height: '30%',
	pagination: true,
	navigation: false,
	fx: 'simpleFade',
	caption: true,
	loader: true,
	playPause: false,
	thumbnails: false
});
</script>
