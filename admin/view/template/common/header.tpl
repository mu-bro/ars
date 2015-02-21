<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
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
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/common.js"></script>
<link rel="stylesheet" type="text/css" href="../catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/font-awesome.css">
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
            if (!confirm('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
        });
    </script>
</head>
<body>
<?php if (isset ($this->session->data['token']) && $this->user->hasPermission('access', 'module/qpanel')) { ?>
<div class="qpanel"></div>
<script type="text/javascript"><!--
$(".qpanel").load("index.php?route=module/qpanel/load&token=<?php echo $this->session->data['token']; ?>");
//--></script>
<?php } ?>
<div id="container">
    <div id="header">
  <div class="div1">
    <div class="div2"><img src="view/image/logo.png" title="<?php echo $heading_title; ?>" onclick="location = '<?php echo $home; ?>'" /></div>
    <?php if ($logged) { ?>
    <div class="div3"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
    <?php } ?>
  </div>
  <?php if ($logged) { ?>
  <div id="menu">
    <ul class="left" style="display: none;">
      <li id="catalog"><a class="top"><?php echo $text_catalog; ?></a>
        <ul>
          <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
          <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
          <li><a class="parent"><?php echo $text_attribute; ?></a>
            <ul>
              <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
              <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
            </ul>
          </li>
          <!--<li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>-->
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>          
          <!--<li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>-->
          <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
          <!--<li><a href="<?php echo $this->url->link("catalog/staff","token=" . $this->session->data['token']); ?>"><?php echo $text_staff; ?></a></li>-->
        </ul>
      </li>
      <li id="extension"><a class="top"><?php echo $text_extension; ?></a>
        <ul>
          <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
          <!--<li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>-->
          <!--<li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>-->
          <!--<li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>-->
          <!--<li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>-->
          <li><a href="<?php echo $menu; ?>"><?php echo $text_menu; ?></a></li>
          <li><a href="<?php echo $this->url->link("catalog/import","token=" . $this->session->data['token']);; ?>">Импорт товаров</a></li>
        </ul>
      </li>
      <li id="system"><a class="top"><?php echo $text_system; ?></a>
        <ul>
          <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
          <li><a class="parent"><?php echo $text_design; ?></a>
            <ul>
              <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
              <li><a target="_blank" href="<?php echo HTTP_CATALOG; ?>index.php?route=common/home&lng_editable=Y"><?php echo $edit_language; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_users; ?></a>
            <ul>
              <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
              <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_localisation; ?></a>
            <ul>
              <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
              <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
        </ul>
      </li>
      <li id="banner"><a class="top" href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a>
      <li id="reports"><a class="top" href="<?php echo $report_product_viewed; ?>"><?php echo $text_reports; ?></a></li>
    </ul>
    <ul class="right" style="display: none;">
      <li id="store"><a href="<?php echo $store; ?>" target="_blank" class="top"><?php echo $text_front; ?></a>
        <ul>
          <?php foreach ($stores as $stores) { ?>
          <li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <li><a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
    </ul>
  </div>
  <?php } ?>
</div>
