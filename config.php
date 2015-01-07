<?php

define('DOMAIN', 'localhost/ars'); // указать свой домен без http:// и слеша в конце
define('DIR_ROOT', str_replace('\\', '/', realpath('.')) . '/');
define('CURRENT_AREA', 'C');

// HTTP
define('HTTP_SERVER', 'http://' . DOMAIN . '/');

// HTTPS
define('HTTPS_SERVER', 'http://' . DOMAIN . '/');

// DIR
define('DIR_APPLICATION', DIR_ROOT . 'catalog/');
define('DIR_SYSTEM', DIR_ROOT . 'system/');
define('DIR_DATABASE', DIR_ROOT . 'system/database/');
define('DIR_LANGUAGE', DIR_ROOT . 'catalog/language/');
define('DIR_TEMPLATE', DIR_ROOT . 'catalog/view/theme/');
define('DIR_CONFIG', DIR_ROOT . 'system/config/');
define('DIR_IMAGE', DIR_ROOT . 'image/');
define('DIR_CACHE', DIR_ROOT . 'system/cache/');
define('DIR_DOWNLOAD', DIR_ROOT . 'download/');
define('DIR_LOGS', DIR_ROOT . 'system/logs/');

require(DIR_ROOT . 'dbconfig.php');

?>