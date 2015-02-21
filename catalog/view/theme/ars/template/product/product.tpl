<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
    <div id="content"><?php echo $content_top; ?>
        <h1><?php echo $heading_title; ?></h1>

        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <div class="product-info">
            <?php if ($thumb || $images) { ?>
                <div class="left">
                    <?php if ($thumb) { ?>
                        <div class="image">
                            <a
                                <?php if ($popup) { ?>
                                    href="<?php echo $popup; ?>" class="colorbox"
                                <?php } ?>
                                title="<?php echo $heading_title; ?>" ><img
                                    src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>"
                                    alt="<?php echo $heading_title; ?>"
                                    id="image"/></a></div>
                    <?php } ?>
                    <?php if ($images) { ?>
                        <div class="image-additional">
                            <?php foreach ($images as $image) { ?>
                                <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"
                                   class="colorbox"><img
                                        src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>"
                                        alt="<?php echo $heading_title; ?>"/></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="right">
                <div class="description">
                    <span><?php echo $text_sku; ?></span> <b><?php echo $sku; ?></b><br/>
                    <?php if ($manufacturer) { ?>
                        <span><?php echo $text_manufacturer; ?></span> <a
                            href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a><br/>
                    <?php } ?>
                    <?php if ($reward) { ?>
                        <span><?php echo $text_reward; ?></span> <?php echo $reward; ?><br/>
                    <?php } ?>

                    <br/>
                    <?php foreach ($attribute_groups as $attribute_group) { ?>
                        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                            <?php echo $attribute['name']; ?> - <?php echo $attribute['text']; ?><br/>
                        <?php } ?>
                    <?php } ?>

                </div>
                <div class="stock <?php echo ($product_info['quantity'] > 0) ? " green
            " : "red"; ?>"><?php echo ($product_info['quantity'] > 0) ? $text_in_stock : $text_out_stock; ?>
                </div>

                <?php if ($options) { ?>
                    <div class="options">
                        <br/>
                        <?php foreach ($options as $option) { ?>
                            <?php if ($option['type'] == 'select') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <select name="option[<?php echo $option['product_option_id']; ?>]" class="custome">
                                        <option value=""><?php echo $text_select; ?></option>
                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                            <option
                                                value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                                <?php if ($option_value['price']) { ?>
                                                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                                <?php } ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'radio') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <?php foreach ($option['option_value'] as $option_value) { ?>
                                        <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]"
                                               value="<?php echo $option_value['product_option_value_id']; ?>"
                                               id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>
                                        <label
                                            for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                            <?php if ($option_value['price']) { ?>
                                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                            <?php } ?>
                                        </label>
                                        <br/>
                                    <?php } ?>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'checkbox') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <?php foreach ($option['option_value'] as $option_value) { ?>
                                        <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]"
                                               value="<?php echo $option_value['product_option_value_id']; ?>"
                                               id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>
                                        <label
                                            for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                            <?php if ($option_value['price']) { ?>
                                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                            <?php } ?>
                                        </label>
                                        <br/>
                                    <?php } ?>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'image') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <table class="option-image">
                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                            <tr>
                                                <td style="width: 1px;"><input type="radio"
                                                                               name="option[<?php echo $option['product_option_id']; ?>]"
                                                                               value="<?php echo $option_value['product_option_value_id']; ?>"
                                                                               id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>
                                                </td>
                                                <td><label
                                                        for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img
                                                            src="<?php echo $option_value['image']; ?>"
                                                            alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>"/></label>
                                                </td>
                                                <td>
                                                    <label
                                                        for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                                        <?php if ($option_value['price']) { ?>
                                                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                                        <?php } ?>
                                                    </label></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'text') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
                                           value="<?php echo $option['option_value']; ?>"/>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'textarea') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40"
                          rows="5"><?php echo $option['option_value']; ?></textarea>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'file') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <input type="button" value="<?php echo $button_upload; ?>"
                                           id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
                                    <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value=""/>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'date') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
                                           value="<?php echo $option['option_value']; ?>" class="date"/>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'datetime') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
                                           value="<?php echo $option['option_value']; ?>" class="datetime"/>
                                </div>
                                <br/>
                            <?php } ?>
                            <?php if ($option['type'] == 'time') { ?>
                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                    <?php if ($option['required']) { ?>
                                        <span class="required">*</span>
                                    <?php } ?>
                                    <b><?php echo $option['name']; ?>:</b><br/>
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
                                           value="<?php echo $option['option_value']; ?>" class="time"/>
                                </div>
                                <br/>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="cart">
                    <?php if ($price) { ?>
                        <div class="price">
                            <div class="priceHolder">
                                <?php if (!$special) { ?>
                                    <?php echo $price; ?>
                                <?php } else { ?>
                                    <span class="price-old"><?php echo $price; ?></span> <span
                                        class="price-new"><?php echo $special; ?></span>
                                <?php } ?>
                            </div>
                            <div class="iconHolder tooltip_block">
                                <a href="index.php?route=information/actions&news_id=17">
                                    <img src="catalog/view/theme/ars/image/delivery.png">
                                </a>
                                <div class="help" style="right:5px">
                                    <div class="help_note"><?php echo $text_tooltip_delivery; ?></div>
                                </div>

                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($sale10)) { ?>
                        <div class="price">
                            <div class="priceHolder">
                                <div class="price-new"><?php echo $sale10; ?></div>
                            </div>
                            <div class="iconHolder tooltip_block">
                                <a href="index.php?route=information/actions&news_id=14">
                                    <img src="catalog/view/theme/ars/image/sale10.png">
                                </a>
                                <div class="help"  style="right:4px">
                                    <div class="help_note"><?php echo $text_tooltip_sale10; ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($oldBatteryPrice)) { ?>
                        <div class="price">
                            <div class="priceHolder">
                                <div class="price-new"><?php echo $oldBatteryPrice; ?></div>
                            </div>
                            <div class="iconHolder tooltip_block">
                                <a href="index.php?route=information/actions&news_id=13">
                                    <img src="catalog/view/theme/ars/image/change.png">
                                </a>
                                <div class="help" >
                                    <div class="help_note"><?php echo $text_tooltip_change; ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($maxDiscountPrice)) { ?>
                        <div class="price total">
                            <div class="priceHolder">
                                <div class="price-new"><?php echo $maxDiscountPrice; ?></div>
                            </div>
                            <div class="iconHolder tooltip_block">

                                    <img src="catalog/view/theme/ars/image/bestPrice.png">

                                <div class="help" style="right:-10px">
                                    <div class="help_note"><?php echo $text_tooltip_best_price; ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <a class="button_grey" id="add_to_cart" href="#quick_order_box"><?php echo $text_buy; ?></a>
            </div>
        </div>
        <div class="main_descr"><?php echo $description; ?></div>
        <?php if ($products) { ?>
            <div class="box-heading orange"><?php echo $tab_related; ?></div>
            <div class="box-product">
                <table class="relatedProducts">
                    <thead>
                    <td><?php echo $text_product; ?></td>
                    <td><?php echo $text_name_art; ?></td>
                    <td><?php echo $text_price; ?></td>
                    <td><?php echo $text_stock; ?></td>
                    </thead>
                    <?php foreach ($products as $product) { ?>
                        <tr>
                            <td>
                                <?php if ($product['thumb']) { ?>
                                    <div class="image"><a href="<?php echo $product['href']; ?>"><img
                                                src="<?php echo $product['thumb']; ?>"
                                                alt="<?php echo $product['name']; ?>"/></a>
                                    </div>
                                <?php } ?>
                            </td>
                            <td style="text-align:left;">
                                <div class="name">
                                    <a href="<?php echo $product['href']; ?>">
                                        <?php echo $product['name']; ?><br/>
                                        <?php echo $text_model; ?>: <?php echo $product['model']; ?>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <?php if ($product['price']) { ?>
                                    <div class="price">
                                        <?php if (!$product['special']) { ?>
                                            <?php echo $product['price']; ?>
                                        <?php } else { ?>
                                            <span class="price-old"><?php echo $product['price']; ?></span> <span
                                                class="price-new"><?php echo $product['special']; ?></span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="stock <?php echo ($product['quantity'] > 0) ? " green
                " : "red"; ?>"><?php echo ($product['quantity'] > 0) ? $text_in_stock : $text_out_stock; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>
        <?php if ($tags) { ?>
            <div class="tags"><b><?php echo $text_tags; ?></b>
                <?php for ($i = 0; $i < count($tags); $i++) { ?>
                    <?php if ($i < (count($tags) - 1)) { ?>
                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
                    <?php } else { ?>
                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php echo $content_bottom; ?></div>
    <script type="text/javascript"><!--
        $(document).ready(function () {
            $('.colorbox').colorbox({
                overlayClose: true,
                opacity: 0.5,
                rel: "colorbox"
            });
        });
        //--></script>

    <script type="text/javascript"><!--
        $('#add_to_cart').fancybox();
        //--></script>
    <div style="display:none;">
        <div id="quick_order_box">
            <h2 class="qhead">Заказ товара</h2>
            <div class="qproduct_form">
                <div class="qimage">
                    <img
                        src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>"
                        alt="<?php echo $heading_title; ?>"1
                        id="image"/>
                </div>
                <div class="qinfo">
                    <div class="qname"><?php echo $heading_title; ?></div>
                    <span class="qsku"><?php echo $text_sku; ?><span> <?php echo $sku; ?></span></span><br/>
                    <div class="qprice">
                        <?php if (!$special) { ?>
                            <?php echo $price; ?>
                        <?php } else { ?>
                            <?php echo $special; ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <p class="sub_text">
                Заполните форму и наш менеджер свяжется с Вами и уточнит все детали заказа!
            </p>
            <form id="qform">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                <table class="form qform">
                    <tr>
                        <td style="width:200px;">
                            <label>Ваше имя: <span class="required">*</span></label><br/>
                            <input type="text" name="name" value="" placeholder="Ваше имя" style="width:170px" />
                        </td>
                        <td style="width:150px;">
                            <label>Ваш телефон: <span class="required">*</span></label><br/>
                            <input type="text" class="phone_mask" name="phone" value="" placeholder="Ваш телефон" style="width:120px"/>
                        </td>
                        <td>
                            <label>Ваш e-mail:</label><br/>
                            <input type="text" name="email" value="" placeholder="Ваш e-mail" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="delivery_type" value="S" id="delivery_type_s" />
                            <label for="delivery_type_s">Заберу сам</label>
                        </td>
                        <td>
                            <input type="radio" name="delivery_type" value="D" id="delivery_type_d"  />
                            <label for="delivery_type_d">Доставка по адресу</label>
                        </td>
                        <td></td>
                    </tr>

                    <tbody id="delivery_type_D_body" class="delivery_type_body">
                        <tr>
                            <td colspan="3">
                                <textarea name="address" placeholder="Адрес доставки"></textarea>
                            </td>
                        </tr>
                    </tbody>
                    <tbody id="delivery_type_S_body" class="delivery_type_body">
                        <tr>
                            <td colspan="3">
                                <select name="shop_address">
                                    <option value="*">- выберите магазин -</option>
                                    <option value="пр-т Нариманова, д. 1а">пр-т Нариманова, д. 1а</option>
                                    <option value="ул. Хрустальная, 35б">ул. Хрустальная, 35б</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                    <tr>
                        <td colspan="3">
                            <label>Комментарий к заказу:</label><br/>
                            <textarea name="comment" placeholder="Комментарий к заказу"></textarea>
                        </td>
                    </tr>
                </table>
            </form>
            <p class="sub_text">
                Перед отправкой внимательно проверьте номер Вашего телефона!
            </p>
            <a id="quick_order_send" class="button big_b blue_b" >Отправить</a>
            <div id="error_label"></div>
        </div>
    </div>
    <script type="text/javascript"><!--


        $(document).ready(function() {
            $('input[name="delivery_type"].').bind('click', function() {
                $('.delivery_type_body').hide();
                delivery_name = $(this).attr("value");
                $('#delivery_type_' + delivery_name +'_body').show();

            });

            $('#quick_order_send').bind('click', function() {
                $.ajax({
                    url: 'index.php?route=product/product/request',
                    type: 'post',
                    dataType: 'json',
                    data: $('#qform').serialize(),
                    beforeSend: function() {
                        $('.success, .warning, .error, .wait').remove();
                        $('#quick_order_send').attr('disabled', true);
                        $('#quick_order_send').after('<img class="wait" src="catalog/view/theme/default/image/loading.gif" alt="" />');
                    },
                    complete: function() {
                        $('#button-review').attr('disabled', false);
                        $('.wait').remove();
                    },
                    success: function(json) {
                        if (json['error']) {
                            position = $('#quick_order_box *[name="'+ json['error_form'] +'"]').position();
                            $('#error_label').html(json['error'] + "<span></span>").css('left',position.left + 10).css('top',
                                position.top - 34).show();
                            $('#quick_order_box *[name="'+ json['error_form'] +'"]').bind('click',function() {
                                $('#error_label').hide();
                                $('#quick_order_box *[name="'+ json['error_form'] +'"]').unbind('click');
                            });
                        }

                        if (json['success']) {
                            $('.fancybox-close').trigger('click');
                            $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                            $('.success').fadeIn('slow');
                            $('html, body').animate({ scrollTop: 0 }, 'slow');
                        }
                    }
                });
            });

            $(function(){
                $.mask.definitions['x'] = '[0-9]';
            });
            $(function(){
                $('.phone_mask').mask("+7 (xxx) xxx xxxx", {placeholder:"_"});
            });


        });
        //--></script>

    <script type="text/javascript"><!--
        $('select[name="profile_id"], input[name="quantity"]').change(function () {
            $.ajax({
                url: 'index.php?route=product/product/getRecurringDescription',
                type: 'post',
                data: $('input[name="product_id"], input[name="quantity"], select[name="profile_id"]'),
                dataType: 'json',
                beforeSend: function () {
                    $('#profile-description').html('');
                },
                success: function (json) {
                    $('.success, .warning, .attention, information, .error').remove();

                    if (json['success']) {
                        $('#profile-description').html(json['success']);
                    }
                }
            });
        });

        $('#button-cart').bind('click', function () {
            $.ajax({
                url: 'index.php?route=checkout/cart/add',
                type: 'post',
                data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
                dataType: 'json',
                success: function (json) {
                    $('.success, .warning, .attention, information, .error').remove();

                    if (json['error']) {
                        if (json['error']['option']) {
                            for (i in json['error']['option']) {
                                $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                            }
                        }

                        if (json['error']['profile']) {
                            $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                        }
                    }

                    if (json['success']) {
                        $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.success').fadeIn('slow');

                        $('#cart-total').html(json['total']);

                        $('html, body').animate({scrollTop: 0}, 'slow');
                    }
                }
            });
        });
        //--></script>
<?php if ($options) { ?>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
    <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'file') { ?>
            <script type="text/javascript"><!--
                new AjaxUpload('#button-option-<?php echo $option['
    product_option_id
    ']; ?>', {
                        action: 'index.php?route=product/product/upload',
                        name: 'file',
                        autoSubmit: true,
                        responseType: 'json',
                        onSubmit: function (file, extension) {
                            $('#button-option-<?php echo $option['
            product_option_id
            ']; ?>'
                            ).
                                after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
                            $('#button-option-<?php echo $option['
            product_option_id
            ']; ?>'
                            ).
                                attr('disabled', true);
                        },
                        onComplete: function (file, json) {
                            $('#button-option-<?php echo $option['
            product_option_id
            ']; ?>'
                            ).
                                attr('disabled', false);

                            $('.error').remove();

                            if (json['success']) {
                                alert(json['success']);

                                $('input[name=\'option[<?php echo $option['
                product_option_id
                ']; ?>]\']'
                                ).
                                    attr('value', json['file']);
                            }

                            if (json['error']) {
                                $('#option-<?php echo $option['
                product_option_id
                ']; ?>'
                                ).
                                    after('<span class="error">' + json['error'] + '</span>');
                            }

                            $('.loading').remove();
                        }
                    }
                )
                ;
                //--></script>
        <?php } ?>
    <?php } ?>
<?php } ?>
    <script type="text/javascript"><!--
        $('#review .pagination a').live('click', function () {
            $('#review').fadeOut('slow');

            $('#review').load(this.href);

            $('#review').fadeIn('slow');

            return false;
        });

        $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

        $('#button-review').bind('click', function () {
            $.ajax({
                url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
                type: 'post',
                dataType: 'json',
                data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
                beforeSend: function () {
                    $('.success, .warning').remove();
                    $('#button-review').attr('disabled', true);
                    $('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
                },
                complete: function () {
                    $('#button-review').attr('disabled', false);
                    $('.attention').remove();
                },
                success: function (data) {
                    if (data['error']) {
                        $('#review-title').after('<div class="warning">' + data['error'] + '</div>');
                    }

                    if (data['success']) {
                        $('#review-title').after('<div class="success">' + data['success'] + '</div>');

                        $('input[name=\'name\']').val('');
                        $('textarea[name=\'text\']').val('');
                        $('input[name=\'rating\']:checked').attr('checked', '');
                        $('input[name=\'captcha\']').val('');
                    }
                }
            });
        });
        //--></script>
    <script type="text/javascript"><!--
        $('#tabs a').tabs();
        //--></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript"><!--
        $(document).ready(function () {
            if ($.browser.msie && $.browser.version == 6) {
                $('.date, .datetime, .time').bgIframe();
            }

            $('.date').datepicker({dateFormat: 'yy-mm-dd'});
            $('.datetime').datetimepicker({
                dateFormat: 'yy-mm-dd',
                timeFormat: 'h:m'
            });
            $('.time').timepicker({timeFormat: 'h:m'});
        });
        $('.help').hover(function() {
            $(this).parent().find('.help_note').show();
        },function () {
            $(this).parent().find('.help_note').hide();
        });
        //--></script>
<?php echo $footer; ?>