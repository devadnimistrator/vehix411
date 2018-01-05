<?php
// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'en_US.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

//define('TEXT_RESULT_PAGE', '<font color=#6ca8e1>Page result of :</font> ');
define('TEXT_DISPLAY_NUMBER_OF_USERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> users)');
define('TEXT_DISPLAY_NUMBER_OF_NEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> news)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> specials)');
define('TEXT_DISPLAY_NUMBER_OF_BRANDS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> brands)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> coupons)');
define('TEXT_DISPLAY_NUMBER_OF_STORES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> locations)');
define('TEXT_DISPLAY_NUMBER_OF_CATEGORIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> categories)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_DEALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> deals)');
define('TEXT_DISPLAY_NUMBER_OF_SHOPPINGS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> shopping lists)');
define('TEXT_DISPLAY_NUMBER_OF_FAVORITES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> favorite products)');
define('TEXT_DISPLAY_NUMBER_OF_FAVORITESTORES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> favorite store lists)');
define('TEXT_DISPLAY_NUMBER_OF_FEEDBACKS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> feedback lists)');
define('TEXT_DISPLAY_NUMBER_OF_CHECKS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> checks)');

define('ERROR_SERVER_PROBLEM', 'Sorry, Server have any problem.');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Next');
define('PREVNEXT_TITLE_PAGE_NO', 'Move');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Prev');

$SYSTEM_MESSAGE['ERROR_MUST_BE_NOT'] = "The %s must not be empty.";
$SYSTEM_MESSAGE['ERROR_MUST_BE_CHECK'] = "The %s must be checked.";
$SYSTEM_MESSAGE['ERROR_MUST_IS_INCORRECT'] = "The %s is incorrect.";
$SYSTEM_MESSAGE['ERROR_MUST_BE_LENGTH'] = "The %s must contain at %d ~ %d of chracters.";
$SYSTEM_MESSAGE['ERROR_NOT_EQUALS'] = "The %s does not match.";
$SYSTEM_MESSAGE['ERROR_NOT_EXIST'] = "The %s does not exist.";
$SYSTEM_MESSAGE['ERROR_ALREADY_EXIST'] = "The %s already exists";
$SYSTEM_MESSAGE['ERROR_USER_ID'] = "This userid is not avaliable.";
$SYSTEM_MESSAGE['ERROR_FORAMT_DATE'] = "This %s is not date format.";
$SYSTEM_MESSAGE['ERROR_FORAMT_DATETIME'] = "This %s is not datetime format.";
$SYSTEM_MESSAGE['ERROR_FORAMT_ZIPCODE'] = "This zipcode is incorrect.";
$SYSTEM_MESSAGE['ERROR_FORAMT_INTEGER'] = "This %s is not integer.";
$SYSTEM_MESSAGE['ERROR_MINIMUM'] = "This %s minimum value is %s.";
$SYSTEM_MESSAGE['ERROR_MAXIMUM'] = "This %s maximum value is %s.";
$SYSTEM_MESSAGE['ERROR_FORAMT_NUMBER'] = "This %s is not number.";
?>