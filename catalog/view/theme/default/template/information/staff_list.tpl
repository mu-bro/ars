<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php foreach ($results as $staff) { ?>
	<div class="manufacturer-list">
		<div class="manufacturer-content">
			<?php if ($staff['image']) { ?>
				<a href="<?php echo $staff['href']; ?>" title="<?php echo $heading_title; ?>" >
					<img src="<?php echo $staff['image']; ?>" title="<?php echo $staff['name']; ?>" alt="<?php echo $staff['name']; ?>" />
				</a>
			<?php } ?>
			<div class="staff_name"><?php echo $staff['name']; ?></div>
			<div class="staff_name"><?php echo $staff['short_descr']; ?></div>

		</div>
	</div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>