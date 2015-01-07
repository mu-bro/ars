<div class="menu_block">
	<div id="<?php echo $style_id; ?>">
	<!--   <div class="box-content"> -->
	<ul>
		<?php $iterat_0 = 0; $first_0 = "first "; foreach( $links as $link_level_0 ) { $iterat_0 ++; ?>
			<li class="<?php echo $first_0; $first_0 = ''; echo empty( $link_level_0["submenu"] ) ? 'no-sub-item' : ''; echo $link_level_0['sub_class']; ?>"

					<?php if( isset( $link_level_0["image"] ) && !empty($link_level_0["image"]) ) { ?>
						style="background: url(<?php echo $link_level_0["image"]; ?>) no-repeat left center;"
					<?php } ?>
													>

				<a <?php if( isset( $link_level_0["color_"] ) ) echo 'style="color:' . $link_level_0["color_"] . '"'; ?> href="<?php echo $link_level_0["href"]; ?>" onclick="<?php echo empty( $link_level_0["href"] ) ? "return false;" : "" ?>" <?php echo ($link_level_0["title"] == 'Idee') ? "class='sz'" : ""; ?> >
					<?php if ($link_level_0["title"] == 'Idee') { ?>
						<img src="catalog/view/theme/florentin/image/idee_icon.png" />
					<?php } else { ?>
						<?php echo $link_level_0["title"]; ?>&nbsp;
					<?php } ?>
				</a>
				<?php if( !empty( $link_level_0["submenu"] ) ){ ?>
				<div class="level_1 level_1_item_<?php echo $iterat_0; ?>" style="padding-top:5px;padding-bottom:5px;">
					<?php $level_1_column_id = 0; ?>
					<!-- Начало группы -->
					<div class="group first-group">
						<div style="display:table;">
						<div style="display:table-cell;">
					<ul style="display:table-cell;" class="column column_<?php echo $level_1_column_id; ?>">
						<?php  $first_1 = "first "; $iterat_1 = 0; foreach( $link_level_0["submenu"] as $link_level_1 ) { $iterat_1 ++; ?>
							<?php if( !empty( $link_level_1["type"] ) && $link_level_1["type"] == "delimiter" ) { ?>
								<!-- Замыкаем группы и создаём заново -->
										</ul>
										</div>
									</div>
								</div>
								<div class="group <?php if( empty( $first_1 ) ) { ?>delimiter<?php } ?>"></div>
								<div class="group" >
									<?php if( !empty( $link_level_1["title"] ) ) { ?><div class="header-group"><?php echo $link_level_1["title"]; ?></div><?php } ?>
									<div style="display:table;">
										<div style="display:table-cell;">
										<ul class="column column_<?php echo $link_level_1["column"]; $level_1_column_id = $link_level_1["column"]; ?>">
								<?php continue; ?>
							<?php } ?>
							<?php if( $link_level_1["column"] != $level_1_column_id ) { $first_1 = "first "; ?>
									<!-- Делим на ячейки по колличеству -->
									</ul>
									</div>
									<div style="display:table-cell;">
									<ul class="column column_<?php echo $link_level_1["column"]; $level_1_column_id = $link_level_1["column"]; ?>">
							<?php } ?>
							<li <?php if( isset( $link_level_0["column_width_"] ) ) echo 'style="width:' . $link_level_0["column_width_"] . 'px;"'; ?> class="<?php echo $first_1; $first_1 = ''; echo empty( $link_level_1["submenu"] ) ? 'no-sub-item' : ''; echo $link_level_1['sub_class']; ?> item_<?php echo $iterat_1; ?>">
								<?php if( empty( $link_level_1["type"] ) || $link_level_1["type"] != 'html' ){ ?>
									<a href="<?php echo $link_level_1["href"]; ?>"  onclick="<?php echo empty( $link_level_1["href"] ) ? "return false;" : "" ?>">
										<?php if( !empty( $link_level_1["image"] ) ) { ?><img class="link_item_img" src="<?php echo $link_level_1["image"];?>" /><?php } ?>
										<?php echo $link_level_1["title"]; ?>
									</a>
								<?php } else { ?>
									<?php if( !empty( $link_level_1["title"] ) ) { ?><h3><?php echo $link_level_1["title"]; ?></h3><?php } ?>
									<?php echo $link_level_1["html"]; ?>
								<?php } ?>
							</li>
						<?php } ?>
					</ul>
							</div>
						</div>
					<!-- Конец группы -->
					</div>
				</div>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
	<!--   </div> -->
	</div>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(function(){
	$('#top_menu > ul > li').hover(
		function() {
			if (!$(this).hasClass('no-sub-item')) {
				$(this).find('.level_1').slideDown();
			}
		}, 
		function() {
			$(this).find('.level_1').slideUp();	
		}
	);
});
</script>