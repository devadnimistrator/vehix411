<?php
define('DEFAULT_TIMEZONE', 'Europe/Warsaw');

define('HTTP_SERVER', "http://" . $_SERVER["SERVER_NAME"]);
// define('HTTP_CATALOG_SERVER', HTTP_SERVER . "/2017/vehix/");
define('HTTP_CATALOG_SERVER', HTTP_SERVER . "/");

define('DIR_WS_CONFIGURE', DIR_FS_DOCUMENT_ROOT . 'config/');
define('DIR_WS_LIBRARY', DIR_FS_DOCUMENT_ROOT . 'library/');
define('DIR_WS_FUNCTIONS', DIR_FS_DOCUMENT_ROOT . 'library/functions/');
define('DIR_WS_CLASSES', DIR_FS_DOCUMENT_ROOT . 'library/classes/');
define('DIR_WS_BOX', DIR_FS_DOCUMENT_ROOT . 'library/box/');
define('CACHE_DIR', DIR_WS_LIBRARY . 'cache/');

define('HTTP_WS_STATIC', HTTP_CATALOG_SERVER . 'static/');
define('HTTP_WS_VIDEOS', HTTP_WS_STATIC . 'videos/');
define('HTTP_WS_IMAGES', HTTP_WS_STATIC . 'images/');

//Session
define('SESSION_NAME', 'vehix411_api');
define('SESSION_USER_ID', 'vehix411_user_id');
define('SESSION_WRITE_DIRECTORY', DIR_WS_LIBRARY . 'cache/');

define('USE_PCONNECT', 'false');
define('STORE_SESSIONS', 'mysql');
define('CHARSET', 'utf8');

$email_config = array(
    'charset' => 'utf-8',
    'protocol' => 'sendmail',
    'mailpath'	=> '/usr/sbin/sendmail -t -i',
    //'mailpath' => 'D:/sendmail/sendmail.exe',
    'mailtype' => 'html'
);
define('ICONV_ENABLED', false);
define('MB_ENABLED', false);

/* upload file */
define('DIR_WS_UPLOAD', DIR_FS_DOCUMENT_ROOT . 'upload/');
define('HTTP_WS_UPLOAD', HTTP_CATALOG_SERVER . 'upload/');

// Password Min, Max Length
define('USER_PASSWORD_MIN_LENGTH', 6);
define('USER_PASSWORD_MAX_LENGTH', 30);

define("VENUE_QRCODE_BASIC_URL", HTTP_CATALOG_SERVER . "qr/");

define('AVATAR_IMAGE_WIDTH', 50);
define('AVATAR_IMAGE_HEIGHT', 50);

define('DEFAULT_MALE_AVATAR', HTTP_WS_UPLOAD . "avatar/male_avatar.png");
define('DEFAULT_FEMALE_AVATAR', HTTP_WS_UPLOAD . "avatar/female_avatar.png");

define('MOBILE_IMAGE_WIDTH', 320);
define('MOBILE_IMAGE_HEIGHT', 180);

define('PRODUCT_IMAGE_COUNT', 3);

$no_login_pages = array(
	"login.php",
	"logout.php",
	"signup.php",
	"forgotpassword.php",
	"resetpassword.php",
	"autoscrape.php"
);

$device_colors = array(
	"phone" => array('#3498DB', '#49A9EA'),
	"tablet" => array('#E74C3C', '#E95E4F'),
	"computer" => array('#9B59B6', '#B370CF')
);

define('ASSETS_ORIGINAL_VERSION', '1.0');
define('ASSETS_CUSTOM_VERSION', '1.0.2');

?>