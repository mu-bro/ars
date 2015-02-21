<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" >
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/font-awesome.css">
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<script type="text/javascript"><!--
<?php if ($stores) { ?>
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
<?php } ?>
$(document).ready(function() {
	$('select.custome').msDropDown();
});
//--></script>
<?php echo $google_analytics; ?>
</head>
<body>
<?php echo $yandex_analytics; ?>
<?php echo $lng_editable;  ?>
<div id="container">
<div id="header">
	<div class="header_top"></div>
	<div class="main_block">
		<?php if ($logo) { ?>
		<div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
		<?php } ?>
		<div id="search">
			<div class="section-choose">
				<span>Просмотр по каталогу</span>
			</div>
			<input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
			<div class="button-search fa fa-search"></div>
		</div>
		<div id="welcome">
			<div class="phones-wrapp right">
				<p class="head-worktime"><?php echo $this->config->get("config_schedule"); ?></p>
				<i class="fa fa-phone left"></i>
				<div class="phones right">
					<p><span><?php echo $this->config->get("config_telephone_pre"); ?></span> <strong><?php echo $this->config->get("config_telephone"); ?></strong></p>
					<p style="float:right;"><span><?php echo $this->config->get("config_telephone2_pre"); ?></span> <strong><?php echo $this->config->get("config_telephone2"); ?></strong></p>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>

<?php foreach ($modules as $module) { ?>
	<?php echo $module; ?>
<?php } ?>

<?php if ($error) { ?>    
    <div class="warning"><?php echo $error ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>
<div id="notification"></div>

<div class="main_content main_block">
