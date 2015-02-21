<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
		<div class="container">
            <div class="row">
                <div class="grid_6">
					<h2><?php echo $text_location; ?></h2>
					<div class="map">
						<div class="map" id="YMapsID1" style="height:404px;width:430px;position:relative;"></div>
					</div>
					<div class="contact-info">
						<div class="address"><?php echo $address; ?></div>
						<?php if ($telephone) { ?>
							<b><?php echo $text_telephone; ?></b>
							<?php echo $telephone . ', ' . $this->config->get('config_telephone2'); ?><br />
						<?php } ?>
						<?php if ($config_email) { ?>
							<b><?php echo $text_email; ?></b>
							<?php echo $config_email; ?><br />
						<?php } ?>
						<?php if ($delivery_email) { ?>
							<b><?php echo $text_delivery_telephone; ?></b>
							<?php echo $delivery_email; ?><br />
						<?php } ?>
						<?php if ($fax) { ?>
							<b><?php echo $text_fax; ?></b>
							<?php echo $fax; ?>
						<?php } ?>
					</div>
                </div>
                <div class="grid_6">
					<h2><?php echo $text_contact; ?></h2>
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="contact-form">
						<div class="content">
						<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" />
						<br />
						<?php if ($error_name) { ?>
						<span class="error"><?php echo $error_name; ?></span>
						<?php } ?>
						<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" />
						<br />
						<?php if ($error_email) { ?>
						<span class="error"><?php echo $error_email; ?></span>
						<?php } ?>
						<textarea name="enquiry" cols="40" rows="10" style="width: 100%;" placeholder="<?php echo $entry_enquiry; ?>"><?php echo $enquiry; ?></textarea>
						<br />
						<?php if ($error_enquiry) { ?>
						<span class="error"><?php echo $error_enquiry; ?></span>
						<?php } ?>
						</div>
						<div class="buttons">
							<input type="submit" value="<?php echo $button_continue; ?>" class="button" />
						</div>
					</form>
                </div>
            </div>
        </div> 

<?php if (!empty($coords['yandex_y'])) { ?>
	<script src="http://api-maps.yandex.ru/2.0/?load=package.standard&mode=debug&lang=ru-RU&modules=pmap" type="text/javascript"></script>
	<script type="text/javascript">
		ymaps.ready(init);
		function init () {
		var myMap = new ymaps.Map('YMapsID1', {
			center: [<?php echo $coords['yandex_y']; ?>,<?php echo $coords['yandex_x']; ?>], // Нижний Новгород
			zoom: 16,
		//	type: "yandex#publicMap",
			behaviors: ["default"],
			type: "yandex#publicMap",

			});

			myMap.controls.add('zoomControl').add('mapTools');

		var myPlacemark = new ymaps.Placemark(
			[<?php echo $coords['yandex_y']; ?>,<?php echo $coords['yandex_x']; ?>], {
				balloonContentHeader: '<strong><?php echo strip_tags($store); ?></strong>',
				balloonContentBody: '<?php echo str_replace("\r\n","<br/>",$this->config->get("config_schedule_bottom")); ?>',
				balloonContentFooter: 'тел.: <?php echo $this->config->get("config_telephone") . ", " . $this->config->get("config_telephone2") ; ?>'
			}, {
				draggable: false,
				hideIconOnBalloonOpen: true
		});
		myMap.geoObjects.add(myPlacemark);
  }
	</script>
<?php } ?>

  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>