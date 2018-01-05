<?php

// Start the clock for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());

define('DIR_FS_DOCUMENT_ROOT', dirname(dirname(__FILE__)) . "/");

// check support for register_globals
if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3)) {
  exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');
}

// Include application configuration parameters
require (DIR_FS_DOCUMENT_ROOT . '/config/configure.php');

global $wpdb;

// include the database functions
require (DIR_WS_CLASSES . 'wp_db.php');
require (DIR_WS_FUNCTIONS . 'database.php');

// include the list of project database tables
require (DIR_WS_CONFIGURE . 'database_tables.php');

function require_wp_db() {
  global $wpdb;
  if ($wpdb) {
    
  } else {
    $wpdb = new wpdb(DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, DB_SERVER);
  }

  return $wpdb;
}

// make a connection to the database... now
require_wp_db();

require (DIR_WS_LIBRARY . 'set_configurations.php');

// some code to solve compatibility issues
require (DIR_WS_FUNCTIONS . 'compatibility.php');

// set the type of request (secure or not)
$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
if (!isset($PHP_SELF))
  $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

// set php_self in the local scope
$PHP_SELF = (isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME']);

// message
require (DIR_WS_CLASSES . 'message.php');
$message_cls = new Message();

require (DIR_WS_LIBRARY . 'english.php');

require (DIR_WS_FUNCTIONS . 'validations.php');
require (DIR_WS_FUNCTIONS . 'general.php');

require DIR_WS_LIBRARY . 'media/media.php';

require (DIR_WS_CLASSES . 'Mobile_Detect.php');
$mobileDetect = new Mobile_Detect();

require dirname(__FILE__) . '/_errorcodes.php';

require_once (DIR_WS_CLASSES . "simple_html_dom.php");

abstract class Webservice {

  var $success_message = "Successed call api.";
  var $result = array();
  var $status = FALSE;
  var $logined_key = "";
  var $logined_user_id = 0;
  var $logined_user = 0;
  var $errorcode = ERRORCODE_INPUT_VALUES;

  abstract function validate();

  abstract function run();

  function json_result() {
    global $message_cls;
    if ($this->errorcode == SUCCESS_CODE) {
      $result = array(
          "errorcode" => $this->errorcode,
          "message" => $this->success_message,
          "result" => $this->result
      );
    } else {
      $errors = array();
      foreach ($message_cls->_errors as $key => $values) {
        for ($i = 0; $i < count($values); $i++) {
          $errors[] = $values[$i];
        }
      }

      $result = array(
          "errorcode" => $this->errorcode,
          "errors" => $errors
      );
    }

    if (isset($this->device) && $this->device == "test") {
      $json_str = json_encode($result, JSON_PRETTY_PRINT);
      $json_str = str_replace("\n", "<br/>", $json_str);
      $json_str = str_replace("    ", "&nbsp;&nbsp;&nbsp;&nbsp;", $json_str);
      die('<div style="font-size: 18px; color:#000; font-family: \"Helvetica Neue\", Roboto, Arial, \"Droid Sans\", sans-serif;">' . $json_str . '</div>');
    } else {
      header('Content-Type: application/json');
      die(json_encode($result));
    }
  }

  function validate_login() {
    global $message_cls, $wpdb;
    $this->logined_user_id = $wpdb->get_var("SELECT `ID` FROM " . TABLE_USERS . " WHERE `loginkey` = '{$this->logined_key}'");
    if ($this->logined_user_id) {
      $wpdb->update(TABLE_USERS, array("last_logined" => tep_now_datetime()), array("ID" => $this->logined_user_id));

      $this->logined_user = $wpdb->get_row("SELECT * FROM " . TABLE_USERS . " WHERE `ID` = " . $this->logined_user_id);
      if ($this->logined_user->status != 2) {
        $this->errorcode = ERRORCODE_SECURITY;
        $message_cls->set_error("user", "Your account has blocked.");
      }

      return TRUE;
    } else {
      $this->errorcode = ERRORCODE_SECURITY;
      $message_cls->set_error("security", "You need to try login.");

      return FALSE;
    }
  }

  function set_database_error() {
    global $message_cls;
    $this->errorcode = ERRORCODE_DATABSE;
    $message_cls->set_error("database", ERROR_MESSAGE_100);
  }

}
