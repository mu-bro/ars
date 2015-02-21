<?php echo $header; ?>

    <div id="content">
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>

        <?php if ($error_warning) { ?>
            <div class="warning"><?php echo $error_warning; ?></div>
        <?php } ?>

        <div class="box">
            <div class="heading">
                <h1><img src="view/image/import.png" alt=""/> <?php echo $heading_title; ?></h1>

                <div class="buttons">
                    <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
                </div>
            </div>

            <div class="content">
                <div id="tabs" class="htabs">
                    <a href="#tab-general"><?php echo $tab_general; ?></a>
                    <a href="#tab-settings">Настройки импорта</a>
                    <a href="#tab-data"><?php echo $tab_data; ?></a>
                </div>

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                    <div id="tab-general">
                        <div id="languages" class="htabs">
                            <?php foreach ($languages as $language) { ?>
                                <a href="#language<?php echo $language['language_id']; ?>">
                                    <img src="view/image/flags/<?php echo $language['image']; ?>"
                                         title="<?php echo $language['name']; ?>"/> <?php echo $language['name']; ?>
                                </a>
                            <?php } ?>
                        </div>

                        <?php foreach ($languages as $language) { ?>
                            <div id="language<?php echo $language['language_id']; ?>">
                                <table class="form">
                                    <tr>
                                        <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                                        <td>
                                            <input type="text"
                                                   name="import_description[<?php echo $language['language_id']; ?>][title]"
                                                   size="100"
                                                   value="<?php echo isset($import_description[$language['language_id']]) ? $import_description[$language['language_id']]['title'] : ''; ?>"/>

                                            <?php if (isset($error_title[$language['language_id']])) { ?>
                                                <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><?php echo $entry_description; ?></td>
                                        <td>
                                            <textarea
                                                name="import_description[<?php echo $language['language_id']; ?>][description]"
                                                id="description<?php echo $language['language_id']; ?>"><?php echo isset($import_description[$language['language_id']]) ? $import_description[$language['language_id']]['description'] : ''; ?></textarea>

                                            <?php if (isset($error_description[$language['language_id']])) { ?>
                                                <span
                                                    class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php } ?>

                        <?php echo $import_version; ?>
                    </div>

                    <div id="tab-settings">
                        <div id="vtab-setting" class="vtabs">
                            <a href="#tab-setting-1" id="setting-1">Настройки импорта</a>
                            <a href="#tab-setting-2" id="setting-2">Продукт</a>
                            <a href="#tab-setting-3" id="setting-3">Категория</a>
                            <a href="#tab-setting-4" id="setting-4">Производитель</a>
                            <a href="#tab-setting-5" id="setting-5">Атрибуты</a>
                            <a href="#tab-setting-6" id="setting-6">Опции</a>
                        </div>

                        <div id="tab-setting-1" class="vtabs-content">
                            <table class="form">
                                <tbody>
                                <tr>
                                    <td>
                                        Формат файла:
                                    </td>
                                    <td>
                                        <select name="setting[1][format]" id="format">
                                            <option
                                                value="1" <?php if (!empty($setting[1]['format']) && $setting[1]['format'] == 1) { ?> selected="selected"<?php } ?>>
                                                *.csv
                                            </option>
                                            <option
                                                value="2" <?php if (!empty($setting[1]['format']) && $setting[1]['format'] == 2) { ?> selected="selected"<?php } ?>>
                                                *.xls
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Кодировка файла:
                                        <br/><span
                                            class="help">Кодировка, в которой был сохранён файл (только для csv файлов)</span>
                                    </td>
                                    <td>
                                        <select name="setting[1][charset]" id="charset">
                                            <option
                                                value="1" <?php if (!empty($setting[1]['charset']) && $setting[1]['charset'] == 1) { ?> selected="selected"<?php } ?>>
                                                UTF-8
                                            </option>
                                            <option
                                                value="2" <?php if (!empty($setting[1]['charset']) && $setting[1]['charset'] == 2) { ?> selected="selected"<?php } ?>>
                                                Windows-1251
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Разделитель поля:
                                        <br/><span class="help">Разделитель полей, который был указан при сохранении файла (только для csv файлов)</span>
                                    </td>
                                    <td>
                                        <select name="setting[1][delimiter_field]" id="delimiter_field">
                                            <option
                                                value="1" <?php if (!empty($setting[1]['delimiter_field']) && $setting[1]['delimiter_field'] == 1) { ?> selected="selected"<?php } ?>>
                                                ,
                                            </option>
                                            <option
                                                value="2" <?php if (!empty($setting[1]['delimiter_field']) && $setting[1]['delimiter_field'] == 2) { ?> selected="selected"<?php } ?>>
                                                ;
                                            </option>
                                            <option
                                                value="3" <?php if (!empty($setting[1]['delimiter_field']) && $setting[1]['delimiter_field'] == 3) { ?> selected="selected"<?php } ?>>
                                                :
                                            </option>
                                            <option
                                                value="4" <?php if (!empty($setting[1]['delimiter_field']) && $setting[1]['delimiter_field'] == 4) { ?> selected="selected"<?php } ?>>
                                                Табуляция
                                            </option>
                                            <option
                                                value="5" <?php if (!empty($setting[1]['delimiter_field']) && $setting[1]['delimiter_field'] == 5) { ?> selected="selected"<?php } ?>>
                                                Пробел
                                            </option>
                                            <option
                                                value="6" <?php if (!empty($setting[1]['delimiter_field']) && $setting[1]['delimiter_field'] == 6) { ?> selected="selected"<?php } ?>>
                                                Другой (Укажите в поле)
                                            </option>
                                        </select>
                                        <input type="text" name="setting[1][delimiter_field_other]" id="delimiter_field_other"
                                               value="<?php echo !empty($setting[1]["delimiter_field_other"]) ? $setting[1]["delimiter_field_other"] : ""; ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Разделитель текста:
                                        <br/><span class="help">Разделитель полей, который был указан при сохранении файла (только для csv файлов)<span>
                                    </td>
                                    <td>
                                        <select name="setting[1][delimiter_text]" id="delimiter_text">
                                            <option
                                                value="1" <?php if (!empty($setting[1]['delimiter_text']) && $setting[1]['delimiter_text'] == 1) { ?> selected="selected"<?php } ?>>
                                                "
                                            </option>
                                            <option
                                                value="2" <?php if (!empty($setting[1]['delimiter_text']) && $setting[1]['delimiter_text'] == 2) { ?> selected="selected"<?php } ?>>
                                                '
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Номер строки с заголовком:
                                    </td>
                                    <td>
                                        <input type="text" name="setting[1][header_line]" id="header_line"
                                               value="<?php echo (isset($setting[1]["header_line"]) && $setting[1]["header_line"] != '') ? $setting[1]["header_line"] : 1; ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Начинать импорт со строки номер:
                                    </td>
                                    <td>
                                        <input type="text" name="setting[1][start_line]" id="start_line"
                                               value="<?php echo !empty($setting[1]["start_line"]) ? $setting[1]["start_line"] : 2; ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Закончить импорт на строке номер:
                                        <br/><span class="help">Оставить поле пустым, если останавливать импорт не нужно<span>
                                    </td>
                                    <td>
                                        <input type="text" name="setting[1][stop_line]" id="stop_line"
                                               value="<?php echo !empty($setting[1]["stop_line"]) ? $setting[1]["stop_line"] : ''; ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Обновлять продукт по полю:
                                    </td>
                                    <td>
                                        <select name="setting[1][update_product]" id="update_product">
                                            <option
                                                value="1" <?php if (!empty($setting[1]['update_product']) && $setting[1]['update_product'] == 1) { ?> selected="selected"<?php } ?>>
                                                Название
                                            </option>
                                            <option
                                                value="2" <?php if (!empty($setting[1]['update_product']) && $setting[1]['update_product'] == 2) { ?> selected="selected"<?php } ?>>
                                                SKU
                                            </option>
                                        </select>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Папка с изображениями:
                                    </td>
                                    <td>
                                        <input type="text" name="setting[1][images_dir]" id="images_dir"
                                               value="<?php echo !empty($setting[1]["images_dir"]) ? $setting[1]["images_dir"] : ''; ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><span class="help">Если оставить поле пустым, тогда изображения будут импортироваться из папки images/data/{имя_поля}. Заметьте, что если в поле {имя_поля} будет указан путь до картинки - он будет добавлен. Например если {имя_поля} будет содержать запись вида [products/img1.jpg] будет взято изображение [images/data/products/img1.jpg] <span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="tab-setting-2" class="vtabs-content">
                            <table class="form">
                                <tr>
                                    <td colspan="3" align="right">
                                        Обязательное поле
                                        <br/><span class="help">Если поставить галочки справа, строки с соответствующим пустым полем будут пропущены</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Название товара:
                                        <br/><span class="help">Укажите имя заголовка поля в файле импорта. Внимание! Имя поля необходимо обернуть в символы {} - например {имя поля}. Вы также можете указать несколько полей из файла импорта, например {имя_поля_1}&nbsp;текст&nbsp;{имя_поля_2}<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input
                                                name="setting[2][product_description][<?php echo $language['language_id']; ?>][name]"
                                                value="<?php echo !empty($setting[2]['product_description'][$language['language_id']]['name']) ? $setting[2]['product_description'][$language['language_id']]['name'] : ''; ?>"
                                                size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_description_required][name]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_description_required']['name']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Мета-тег "Описание":
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input
                                                name="setting[2][product_description][<?php echo $language['language_id']; ?>][meta_description]"
                                                value="<?php echo !empty($setting[2]['product_description'][$language['language_id']]['meta_description']) ? $setting[2]['product_description'][$language['language_id']]['meta_description'] : ''; ?>"
                                                size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_description_required][meta_description]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_description_required']['meta_description']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Мета-тег "Ключевые слова":
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input
                                                name="setting[2][product_description][<?php echo $language['language_id']; ?>][meta_keyword]"
                                                value="<?php echo !empty($setting[2]['product_description'][$language['language_id']]['meta_keyword']) ? $setting[2]['product_description'][$language['language_id']]['meta_keyword'] : ''; ?>"
                                                size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_description_required][meta_keyword]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_description_required']['meta_keyword']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Описание:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input
                                                name="setting[2][product_description][<?php echo $language['language_id']; ?>][description]"
                                                value="<?php echo !empty($setting[2]['product_description'][$language['language_id']]['description']) ? $setting[2]['product_description'][$language['language_id']]['description'] : ''; ?>"
                                                size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_description_required][description]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_description_required']['description']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Теги товара:
                                        <br/><span class="help">Перечислите поля через запятую. Пример: {имя_поля_1}, {имя_поля_2} {имя_поля_3}.<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input name="setting[2][product_tag][<?php echo $language['language_id']; ?>]"
                                                   value="<?php echo !empty($setting[2]['product_tag'][$language['language_id']]) ? $setting[2]['product_tag'][$language['language_id']] : ''; ?>"
                                                   size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_required][product_tag]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_required']['product_tag']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Изображение товара:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[2][image]"
                                               value="<?php echo !empty($setting[2]['image']) ? $setting[2]['image'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input name="setting[2][product_required][image]"
                                                                                      type="checkbox"
                                                                                      value="on" <?php echo !empty($setting[2]['product_required']['image']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Дополнительные изображения товара:
                                        <br/><span class="help">Перечислите список полей через разделитель или укажите поле где перечислены адреса изображений через разделитель. Пример: {имя_поля_1}, {имя_поля_2} или {имя_поля_1} <span>
                                    </td>
                                    <td>
                                        <input name="setting[2][additional_images]"
                                               value="<?php echo !empty($setting[2]['additional_images']) ? $setting[2]['additional_images'] : ''; ?>"
                                               size="64"/>
                                        <input name="setting[2][additional_images_delimeter]"
                                               value="<?php echo !empty($setting[2]['additional_images_delimeter']) ? $setting[2]['additional_images_delimeter'] : ','; ?>"
                                               size="3"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_required][additional_images]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_required']['additional_images']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Модель:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[2][model]"
                                               value="<?php echo !empty($setting[2]['model']) ? $setting[2]['model'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input name="setting[2][product_required][model]"
                                                                                      type="checkbox"
                                                                                      value="on" <?php echo !empty($setting[2]['product_required']['model']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Артикул (SKU, код производителя):
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[2][sku]"
                                               value="<?php echo !empty($setting[2]['sku']) ? $setting[2]['sku'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input name="setting[2][product_required][sku]"
                                                                                      type="checkbox"
                                                                                      value="on" <?php echo !empty($setting[2]['product_required']['sku']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        UPC:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[2][upc]"
                                               value="<?php echo !empty($setting[2]['upc']) ? $setting[2]['upc'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input name="setting[2][product_required][upc]"
                                                                                      type="checkbox"
                                                                                      value="on" <?php echo !empty($setting[2]['product_required']['upc']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Расположение:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[2][location]"
                                               value="<?php echo !empty($setting[2]['location']) ? $setting[2]['location'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_required][location]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_required']['location']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Количество:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[2][quantity]"
                                               value="<?php echo !empty($setting[2]['quantity']) ? $setting[2]['quantity'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input
                                                name="setting[2][product_required][quantity]" type="checkbox"
                                                value="on" <?php echo !empty($setting[2]['product_required']['quantity']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Цена:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[2][price]"
                                               value="<?php echo !empty($setting[2]['price']) ? $setting[2]['price'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                    <td valign="middle" align="center" nowrap="nowrap">
                                        <label><span class="required">*</span> <input name="setting[2][product_required][price]"
                                                                                      type="checkbox"
                                                                                      value="on" <?php echo !empty($setting[2]['product_required']['price']) ? 'checked="checked"' : ''; ?>
                                            " /></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        Наценки:
                                        <br/><span class="help">Укажите цифрами интервал цен и процент наценки. Цены, которые не попадут в интервал будут импортированы как есть.<span>
                                    </td>
                                    <td colspan="2">
                                        <table id="markup" class="list">
                                            <thead>
                                            <tr>
                                                <td class="left">От / До:</td>
                                                <td class="left">Наценка (%):</td>
                                                <td class="left">Наценка (+):</td>
                                                <td class="left">Округлить:</td>
                                                <td></td>
                                            </tr>
                                            </thead>
                                            <?php $markup_row = 0; ?>
                                            <?php if (!empty($setting[2]['markup'])) foreach ($setting[2]['markup'] as $markup_value) { ?>
                                                <tbody id="markup-row<?php echo $markup_row; ?>">
                                                <tr>
                                                    <td class="left">
                                                        <input type="text"
                                                               name="setting[2][markup][<?php echo $markup_row; ?>][ot]"
                                                               value="<?php echo (int)$markup_value["ot"]; ?>" size="7"> -
                                                        <input type="text"
                                                               name="setting[2][markup][<?php echo $markup_row; ?>][do]"
                                                               value="<?php echo (int)$markup_value["do"]; ?>" size="7">
                                                    </td>
                                                    <td class="left">
                                                        <input type="text"
                                                               name="setting[2][markup][<?php echo $markup_row; ?>][percent]"
                                                               value="<?php echo $markup_value["percent"]; ?>" size="5">
                                                    </td>
                                                    <td class="left">
                                                        <input type="text"
                                                               name="setting[2][markup][<?php echo $markup_row; ?>][add]"
                                                               value="<?php echo $markup_value["add"]; ?>" size="5">
                                                    </td>
                                                    <td class="left">
                                                        <input type="text"
                                                               name="setting[2][markup][<?php echo $markup_row; ?>][rounding]"
                                                               value="<?php echo $markup_value["rounding"]; ?>" size="3">
                                                    </td>
                                                    <td class="left">
                                                        <a onclick="$('#markup-row<?php echo $markup_row; ?>').remove();"
                                                           class="button">Удалить</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <?php $markup_row++; ?>
                                            <?php } ?>
                                            <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="left">
                                                    <a onclick="addMarkup();" class="button">Добавить наценку</a>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        <span class="help"><br/>Если указать поле "Округлить", тогда на стоимость товара будет применено округление. Указывать можно число отрицательное (-1, -2, -3 и т.д.) или положительное (1, 2, 3 и т.д.). Число означает порядок округления. например что бы округлить до десятых - необходимо указать [1], если окрулить до десяти то [-1], что бы оставить только целое число - [0]</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Cтатус продукта:
                                        <br/><span class="help">Статус продукта будет виден если оставить поле [Количество] пустым.<span>
                                    </td>
                                    <td colspan="2">
                                        <select name="setting[2][stock_status_id]" id="status">
                                            <?php foreach ($statuses as $stock_status) { ?>
                                                <option <?php if (!empty($setting[2]['stock_status_id']) && $setting[2]['stock_status_id'] == $stock_status["stock_status_id"]) { ?> selected="selected"<?php } ?>
                                                    value="<?php echo $stock_status["stock_status_id"]; ?>"><?php echo $stock_status["name"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div id="tab-setting-3" class="vtabs-content">
                            <table class="form">
                                <tr>
                                    <td>
                                        Разделитель пути категории:
                                        <br/><span class="help">Пример: [,] или [/]. Укажите для вложенности категории<span>
                                    </td>
                                    <td>
                                        <input name="setting[3][category_delimiter]"
                                               value="<?php echo !empty($setting[3]['category_delimiter']) ? $setting[3]['category_delimiter'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Импортировать в категории:
                                        <br/><span class="help">Пример: {имя_поля_1}, {имя_поля_1}. Несуществующие категории будут созданы<span>
                                    </td>
                                    <td>
                                        <input name="setting[3][category_path]"
                                               value="<?php echo !empty($setting[3]['category_path']) ? $setting[3]['category_path'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Мета-тег "Описание":
                                        <br/><span class="help">Пример: {имя_поля_1}<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input
                                                name="setting[3][category_description][<?php echo $language['language_id']; ?>][meta_description]"
                                                value="<?php echo !empty($setting[3]['category_description'][$language['language_id']]['meta_description']) ? $setting[3]['category_description'][$language['language_id']]['meta_description'] : ''; ?>"
                                                size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Мета-тег "Ключевые слова":
                                        <br/><span class="help">Пример: {имя_поля_1}<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input
                                                name="setting[3][category_description][<?php echo $language['language_id']; ?>][meta_keyword]"
                                                value="<?php echo !empty($setting[3]['category_description'][$language['language_id']]['meta_keyword']) ? $setting[3]['category_description'][$language['language_id']]['meta_keyword'] : ''; ?>"
                                                size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Описание:
                                        <br/><span class="help">Пример: {имя_поля_1}<span>
                                    </td>
                                    <td>
                                        <?php foreach ($languages as $language) { ?>
                                            <input
                                                name="setting[3][category_description][<?php echo $language['language_id']; ?>][description]"
                                                value="<?php echo !empty($setting[3]['category_description'][$language['language_id']]['description']) ? $setting[3]['category_description'][$language['language_id']]['description'] : ''; ?>"
                                                size="100"/>
                                            <img src="view/image/flags/<?php echo $language['image']; ?>"
                                                 title="<?php echo $language['name']; ?>"/><br/>
                                        <?php } ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Столбец с названием категорий:
                                        <br/><span class="help">Если название категорий находятся не в строке с продуктом, а на отдельной строке и продукты этой категории находятся под названием категории, тогда укажите имя столбца с этим полем. <br/>Пример: {Имя_поля_1}.<span>
                                    </td>
                                    <td>
                                        <input name="setting[3][category_column]"
                                               value="<?php echo !empty($setting[3]['category_column']) ? $setting[3]['category_column'] : ''; ?>"
                                               size="64"/>
                                        <br/><span class="help" style="color:red;">Внимание! В импорте появится поле {generated_category}, его необходимо указать в поле "Импортировать в категории"</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Вложенность категорий:
                                        <br/><span class="help">Укажите вложенность категорий<span>
                                    </td>
                                    <td>
                                        <select name="setting[3][category_nesting]">
                                            <option
                                                value="1" <?php if (!empty($setting[3]['category_nesting']) && $setting[3]['category_nesting'] == 1) { ?> selected="selected"<?php } ?>>
                                                1
                                            </option>
                                            <option
                                                value="2" <?php if (!empty($setting[3]['category_nesting']) && $setting[3]['category_nesting'] == 2) { ?> selected="selected"<?php } ?>>
                                                2
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div id="tab-setting-4" class="vtabs-content">
                            <table class="form">
                                <tr>
                                    <td>
                                        Производитель:
                                        <br/><span class="help">Пример: {имя_поля}<span>
                                    </td>
                                    <td>
                                        <input name="setting[4][name]"
                                               value="<?php echo !empty($setting[4]['name']) ? $setting[4]['name'] : ''; ?>"
                                               size="64"/>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div id="tab-setting-5" class="vtabs-content">
                            <div>
                                <table class="form">
                                    <tr>
                                        <td>Основная группа атрибутов:
                                            <span class="help">Укажите поле, занчение которого будет являться основной группой атрибутов для данного товара. Например {имя_поля}.</span>
                                        </td>
                                        <td>
                                            <input name="setting[5][group]"
                                                   value="<?php echo !empty($setting[5]['group']) ? $setting[5]['group'] : ''; ?>"
                                                   size="64"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">Список полей атрибутов:
                                            <span class="help">Укажите поля, которые необходимо импортировать как атрибуты.</span>
                                        </td>
                                        <td>
                                            <table id="attributes" class="list">
                                                <thead>
                                                <tr>
                                                    <td class="left">Группа:</td>
                                                    <td class="left">Имя поля:</td>
                                                    <td class="left">Название атрибута:</td>
                                                    <td></td>
                                                </tr>
                                                </thead>
                                                <?php $attributes_row = 0; ?>
                                                <?php if (!empty($setting[5]['attributes'])) { ?>
                                                    <?php foreach ($setting[5]['attributes'] as $attributes_value) { ?>
                                                        <tbody id="attributes-row<?php echo $attributes_row; ?>">
                                                        <tr>
                                                            <td class="left">
                                                                <input type="text"
                                                                       name="setting[5][attributes][<?php echo $attributes_row; ?>][group]"
                                                                       value="<?php echo $attributes_value["group"]; ?>">
                                                            </td>
                                                            <td class="left">
                                                                <input type="text"
                                                                       name="setting[5][attributes][<?php echo $attributes_row; ?>][field]"
                                                                       value="<?php echo $attributes_value["field"]; ?>">
                                                            </td>
                                                            <td class="left">
                                                                <input type="text"
                                                                       name="setting[5][attributes][<?php echo $attributes_row; ?>][name]"
                                                                       value="<?php echo $attributes_value["name"]; ?>">
                                                            </td>
                                                            <td class="left">
                                                                <a onclick="$('#attributes-row<?php echo $attributes_row; ?>').remove();"
                                                                   class="button">Удалить</a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                        <?php $attributes_row++; ?>
                                                    <?php } ?>
                                                <?php } ?>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="left">
                                                        <a onclick="addAttributes();" class="button">Добавить атрибут</a>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <span class="help"><br/>Группа атрибутов - это группа в которой будет создан атрибут если данное поле заполнить. Если поле оставить пустым, тогда атрибуты будут присваиваться к основной группе атрибутов. Если поле атрибут в файле импорта будет иметь пустое значение - он не будет импортироваться. Так же если атрибут не получит группы - он не будет импортирован. Настройка "Название атрибута" - это то, как будет называться сам атрибут.</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">Поиск по диапазону полей:</td>
                                        <td>
                                            <input name="setting[5][start_find]"
                                                   value="<?php echo !empty($setting[5]['start_find']) ? $setting[5]['start_find'] : ''; ?>"
                                                   size="30"/>
                                            <input name="setting[5][stop_find]"
                                                   value="<?php echo !empty($setting[5]['stop_find']) ? $setting[5]['stop_find'] : ''; ?>"
                                                   size="30"/>
                                            <span class="help"><br/>Будут добавлены атрибуты, поля которых попадают в заданный диапазон полей. Группой атрибутов станет "Основная группа атрибутов", а названием - название поля атрибутов. <!--Если вы укажете поля здесь, тогда настройки выше работать не будут.--><br/>Вы можете указать только первое поле. В таком случае диапазон будет начинаться от указанного поля и до конца.</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tab-setting-6" class="vtabs-content">
                            <table id="options" class="list">
                                <thead>
                                <tr>
                                    <td class="left">Настройки опции:</td>
                                    <td class="left">Настройки значений опции:</td>
                                    <td></td>
                                </tr>
                                </thead>

                                <?php $options_row = 0; ?>
                                <?php $option_values_row = 0; ?>

                                <?php if (!empty($setting[6]['options'])) { ?>
                                    <?php foreach ($setting[6]['options'] as $option) { ?>
                                        <tbody id="options-row<?php echo $options_row; ?>">
                                        <tr>
                                            <td class="left">
                                                <table class="list">
                                                    <tr>
                                                        <td class="left" style="background-color: #EFEFEF;">Опция:</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="right">
                                                            <select
                                                                name="setting[6][options][<?php echo $options_row; ?>][option_id]">
                                                                <?php foreach ($options as $_option) { ?>
                                                                    <?php if ($option['option_id'] == $_option['option_id']) { ?>
                                                                        <option value="<?php echo $_option['option_id']; ?>"
                                                                                selected="selected">
                                                                            <?php echo $_option['name']; ?>
                                                                        </option>
                                                                    <?php } else { ?>
                                                                        <option value="<?php echo $_option['option_id']; ?>">
                                                                            <?php echo $_option['name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="left" style="background-color: #EFEFEF;">Необходимо:</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="right">
                                                            <select
                                                                name="setting[6][options][<?php echo $options_row; ?>][required]">
                                                                <option
                                                                    value="1"<?php echo ($option['required'] == 1) ? ' selected="selected"' : ''; ?>>
                                                                    Да
                                                                </option>
                                                                <option
                                                                    value="0"<?php echo ($option['required'] == 0) ? ' selected="selected"' : ''; ?>>
                                                                    Нет
                                                                </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                            <td class="left" style="vertical-align: top; width: 663px;">
                                                <div style="height: 310px; overflow-y: auto;">
                                                    <table id="option_value_rows_<?php echo $options_row; ?>" class="list">
                                                        <thead>
                                                        <tr>
                                                            <td class="left" style="background-color: #EFEFEF;">Значение опции:
                                                            </td>
                                                            <td class="left" style="background-color: #EFEFEF;">Количество:</td>
                                                            <td class="left" style="background-color: #EFEFEF;">Вычитать со
                                                                склада:
                                                            </td>
                                                            <td class="left" style="background-color: #EFEFEF;">Цена:</td>
                                                            <td class="left" style="background-color: #EFEFEF;">Баллы:</td>
                                                            <td class="left" style="background-color: #EFEFEF;">Вес:</td>
                                                            <td style="background-color: #EFEFEF;"></td>
                                                        </tr>
                                                        </thead>

                                                        <?php if (!isset($option['values']) || empty($option['values'])) $option['values'] = array(); ?>

                                                        <?php foreach ($option['values'] as $option_value) { ?>
                                                            <tbody
                                                                id="option_row-<?php echo $options_row; ?>-option_value_row-<?php echo $option_values_row; ?>">
                                                            <tr>
                                                                <td class="left">[[[<?php echo $option_values_row; ?>]]]
                                                                    <input type="text"
                                                                           name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][option_value_name]"
                                                                           value="<?php echo $option_value['option_value_name']; ?>"
                                                                           size="5"/>
                                                                </td>
                                                                <td class="left">
                                                                    <input type="text"
                                                                           name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][quantity]"
                                                                           value="<?php echo $option_value['quantity']; ?>"
                                                                           size="5"/>
                                                                </td>
                                                                <td class="left">
                                                                    <select
                                                                        name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][subtract]">
                                                                        <option
                                                                            value="1"<?php echo ($option_value['subtract'] == 1) ? ' selected="selected"' : ''; ?>>
                                                                            Да
                                                                        </option>
                                                                        <option
                                                                            value="0"<?php echo ($option_value['subtract'] == 0) ? ' selected="selected"' : ''; ?>>
                                                                            Нет
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td class="left">
                                                                    <select
                                                                        name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][price_prefix]">
                                                                        <option
                                                                            value="+"<?php echo ($option_value['price_prefix'] == '+') ? ' selected="selected"' : ''; ?>>
                                                                            +
                                                                        </option>
                                                                        <option
                                                                            value="-"<?php echo ($option_value['price_prefix'] == '-') ? ' selected="selected"' : ''; ?>>
                                                                            -
                                                                        </option>
                                                                    </select>

                                                                    <input type="text"
                                                                           name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][price]"
                                                                           value="<?php echo $option_value['price']; ?>"
                                                                           size="5"/>
                                                                </td>
                                                                <td class="left">
                                                                    <select
                                                                        name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][points_prefix]">
                                                                        <option
                                                                            value="+"<?php echo ($option_value['points_prefix'] == '+') ? ' selected="selected"' : ''; ?>>
                                                                            +
                                                                        </option>
                                                                        <option
                                                                            value="-"<?php echo ($option_value['points_prefix'] == '-') ? ' selected="selected"' : ''; ?>>
                                                                            -
                                                                        </option>
                                                                    </select>

                                                                    <input type="text"
                                                                           name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][points]"
                                                                           value="<?php echo $option_value['points']; ?>"
                                                                           size="5"/>
                                                                </td>
                                                                <td class="left">
                                                                    <select
                                                                        name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][weight_prefix]">
                                                                        <option
                                                                            value="+"<?php echo ($option_value['weight_prefix'] == '+') ? ' selected="selected"' : ''; ?>>
                                                                            +
                                                                        </option>
                                                                        <option
                                                                            value="-"<?php echo ($option_value['weight_prefix'] == '-') ? ' selected="selected"' : ''; ?>>
                                                                            -
                                                                        </option>
                                                                    </select>

                                                                    <input type="text"
                                                                           name="setting[6][options][<?php echo $options_row; ?>][values][<?php echo $option_values_row; ?>][weight]"
                                                                           value="<?php echo $option_value['weight']; ?>"
                                                                           size="5"/>
                                                                </td>
                                                                <td class="center">
                                                                    <a onclick="$('#option_row-<?php echo $options_row; ?>-option_value_row-<?php echo $option_values_row; ?>').remove();"
                                                                       class="button">Удалить значение</a>
                                                                </td>
                                                            </tr>
                                                            </tbody>

                                                            <?php $option_values_row++; ?>
                                                        <?php } ?>

                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="6"></td>
                                                            <td class="center">
                                                                <a onclick="addOptionValue('<?php echo $options_row; ?>');"
                                                                   class="button">Добавить значение</a>
                                                            </td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </td>

                                            <td class="center">
                                                <a onclick="$('#options-row<?php echo $options_row; ?>').remove();"
                                                   class="button">Удалить</a>
                                            </td>
                                        </tr>
                                        </tbody>

                                        <?php $options_row++; ?>
                                    <?php } ?>
                                <?php } ?>

                                <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="center">
                                        <a onclick="addOptions();" class="button">Добавить опцию</a>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div id="tab-data">
                        <table class="form">
                            <tr>
                                <td>Порядок сортировки:</td>
                                <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1"/></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $('#tabs a').tabs();
        $('#languages a').tabs();
        $('#vtab-setting a').tabs();

        <?php foreach($languages as $language) { ?>
        CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
            filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
        });
        <?php } ?>

        var markup_row = <?php echo $markup_row; ?>;

        function addMarkup() {
            html = '<tbody id="markup-row' + markup_row + '">';
            html += '  <tr>';
            html += '      <td class="left">';
            html += '          <input type="text" name="setting[2][markup][' + markup_row + '][ot]" value="0" size="7"> -';
            html += '          <input type="text" name="setting[2][markup][' + markup_row + '][do]" value="0" size="7">';
            html += '      </td>';
            html += '      <td class="left">';
            html += '          <input type="text" name="setting[2][markup][' + markup_row + '][percent]" value="" size="5" >';
            html += '      </td>';
            html += '      <td class="left">';
            html += '          <input type="text" name="setting[2][markup][' + markup_row + '][add]" value="" size="5">';
            html += '      </td>';
            html += '      <td class="left">';
            html += '          <input type="text" name="setting[2][markup][' + markup_row + '][rounding]" value="" size="3">';
            html += '      </td>';
            html += '      <td class="left">';
            html += '          <a onclick="$(\'#markup-row' + markup_row + '\').remove();" class="button">Удалить</a>';
            html += '      </td>';
            html += '  </tr>';
            html += '</tbody>';

            $('#markup tfoot').before(html);

            markup_row++;
        }

        var attributes_row = <?php echo $attributes_row; ?>;

        function addAttributes() {
            html = '<tbody id="attributes-row' + attributes_row + '">';
            html += '  <tr>';
            html += '      <td class="left">';
            html += '          <input type="text" name="setting[5][attributes][' + attributes_row + '][group]" value="">';
            html += '      </td>';
            html += '      <td class="left">';
            html += '          <input type="text" name="setting[5][attributes][' + attributes_row + '][field]" value="">';
            html += '      </td>';
            html += '      <td class="left">';
            html += '          <input type="text" name="setting[5][attributes][' + attributes_row + '][name]" value="">';
            html += '      </td>';
            html += '      <td class="left">';
            html += '          <a onclick="$(\'#attributes-row' + attributes_row + '\').remove();" class="button">Удалить</a>';
            html += '      </td>';
            html += '  </tr>';
            html += '</tbody>';

            $('#attributes tfoot').before(html);

            attributes_row++;
        }

        var options_row = <?php echo $options_row; ?>;

        function addOptions() {
            html = '<tbody id="options-row' + options_row + '">';
            html += '	<tr>';
            html += '		<td class="left">';
            html += '			<table class="list">';
            html += '				<tr>';
            html += '					<td class="left" style="background-color: #EFEFEF;">Опция:</td>';
            html += '				</tr>';
            html += '				<tr>';
            html += '					<td class="right">';
            html += '						<select name="setting[6][options][' + options_row + '][option_id]">';
            <?php foreach($options as $_option) { ?>
            html += '							<option value="<?php echo $_option['option_id']; ?>"><?php echo $_option['name']; ?></option>';
            <?php } ?>
            html += '						</select>';
            html += '					</td>';
            html += '				</tr>';
            html += '				<tr>';
            html += '					<td class="left" style="background-color: #EFEFEF;">Необходимо:</td>';
            html += '				</tr>';
            html += '				<tr>';
            html += '					<td class="right">';
            html += '						<select name="setting[6][options][' + options_row + '][required]">';
            html += '							<option value="1">Да</option>';
            html += '							<option value="0">Нет</option>';
            html += '						</select>';
            html += '					</td>';
            html += '				</tr>';
            html += '			</table>';
            html += '		</td>';
            html += '		<td class="left" style="vertical-align: top; width: 663px;">';
            html += '			<div style="height: 310px; overflow-y: auto;">';
            html += '				<table id="option_value_rows_' + options_row + '" class="list">';
            html += '					<thead>';
            html += '						<tr>';
            html += '							<td class="left" style="background-color: #EFEFEF;">Значение опции:</td>';
            html += '							<td class="left" style="background-color: #EFEFEF;">Количество:</td>';
            html += '							<td class="left" style="background-color: #EFEFEF;">Вычитать со склада:</td>';
            html += '							<td class="left" style="background-color: #EFEFEF;">Цена:</td>';
            html += '							<td class="left" style="background-color: #EFEFEF;">Баллы:</td>';
            html += '							<td class="left" style="background-color: #EFEFEF;">Вес:</td>';
            html += '							<td style="background-color: #EFEFEF;"></td>';
            html += '						</tr>';
            html += '					</thead>';

            html += '					<tfoot>';
            html += '						<tr>';
            html += '							<td colspan="6"></td>';
            html += '							<td class="center">';
            html += '								<a onclick="addOptionValue(\'' + options_row + '\');" class="button">Добавить значение</a>';
            html += '							</td>';
            html += '						</tr>';
            html += '					</tfoot>';
            html += '				</table>';
            html += '			</div>';
            html += '		</td>';
            html += '		<td class="center">';
            html += '			<a onclick="$(\'#options-row' + options_row + '\').remove();" class="button">Удалить</a>';
            html += '		</td>';
            html += '	</tr>';
            html += '</tbody>';

            $('#options > tfoot').before(html);

            options_row++;
        }

        var option_values_row = <?php echo $option_values_row; ?>;

        function addOptionValue(opt_row) {
            html = '<tbody id="option_row-' + opt_row + '-option_value_row-' + option_values_row + '">';
            html += '	<tr>';
            html += '		<td class="left">';
            html += '			<input type="text" name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][option_value_name]" value="" size="5" />';
            html += '		</td>';
            html += '		<td class="left">';
            html += '			<input type="text" name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][quantity]" value="" size="5" />';
            html += '		</td>';
            html += '		<td class="left">';
            html += '			<select name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][subtract]">';
            html += '				<option value="1">Да</option>';
            html += '				<option value="0">Нет</option>';
            html += '			</select>';
            html += '		</td>';
            html += '		<td class="left">';
            html += '			<select name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][price_prefix]">';
            html += '				<option value="+">+</option>';
            html += '				<option value="-">-</option>';
            html += '			</select>';
            html += '			<input type="text" name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][price]" value="" size="5" />';
            html += '		</td>';
            html += '		<td class="left">';
            html += '			<select name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][points_prefix]">';
            html += '				<option value="+">+</option>';
            html += '				<option value="-">-</option>';
            html += '			</select>';
            html += '			<input type="text" name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][points]" value="" size="5" />';
            html += '		</td>';
            html += '		<td class="left">';
            html += '			<select name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][weight_prefix]">';
            html += '				<option value="+">+</option>';
            html += '				<option value="-">-</option>';
            html += '			</select>';
            html += '			<input type="text" name="setting[6][options][' + opt_row + '][values][' + option_values_row + '][weight]" value="" size="5" />';
            html += '		</td>';
            html += '		<td class="center">';
            html += '			<a onclick="$(\'#option_row-' + opt_row + '-option_value_row-' + option_values_row + '\').remove();" class="button">Удалить значение</a>';
            html += '		</td>';
            html += '	</tr>';
            html += '</tbody>';

            $('#option_value_rows_' + opt_row + ' tfoot').before(html);

            option_values_row++;
        }
</script>

<?php echo $footer; ?>