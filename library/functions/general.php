<?php

/*
  $Id: general.php v 1.0 2012/07/10 $

  -----------------------------------------------
  Copyright (c) 2012 ChengTong-Yilin
  http://www.chengtong-yilin.com
  -----------------------------------------------
 */

include_once dirname(__FILE__) . '/extends.php';

function curPageURL() {
  $pageURL = 'http';
  if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $pageURL .= "s";
  }
  $pageURL .= "://";
  if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  } else {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return "" . $pageURL;
}

function tep_sanitize_string($string) {
  //$string = ereg_replace(' +', ' ', $string);

  return preg_replace("/[<>]/", '_', $string);
}

function tep_redirect($url) {
  global $logger;

  if ((strstr($url, "\n") != false) || (strstr($url, "\r") != false)) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
  }

  header('Location: ' . $url);

  if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger))
      $logger = new logger;
    $logger->timer_stop();
  }

  exit;
}

function tep_rand($min = null, $max = null) {
  static $seeded;

  if (!$seeded) {
    mt_srand((double) microtime() * 1000000);
    $seeded = true;
  }

  if (isset($min) && isset($max)) {
    if ($min >= $max) {
      return $min;
    } else {
      return mt_rand($min, $max);
    }
  } else {
    return mt_rand();
  }
}

function tep_now_datetime() {
  return date('Y-m-d H:i:s');
}

function tep_get_mktime($date) {
  if (strlen($date) == 10) {
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);

    return mktime(0, 0, 0, $month, $day, $year);
  } elseif (strlen($date) == 19) {
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);
    $hour = substr($date, 11, 2);
    $minute = substr($date, 14, 2);
    $second = substr($date, 17, 2);

    return mktime($hour, $minute, $second, $month, $day, $year);
  }

  return time();
}

function tep_format_time($date, $show_time = false) {
  $date = tep_get_mktime($date);

  $str = date('jS', $date) . " of " . date('F, Y', $date);

  if ($show_time) {
    return date('F j, Y', $date) . " at " . date('H:i', $date);
  } else {
    return date('jS', $date) . " of " . date('F, Y', $date);
  }
}

function tep_cut_str($str, $len, $suffix = "...") {
  $s = substr($str, 0, $len);
  $cnt = 0;
  for ($i = 0; $i < strlen($s); $i++)
    if (ord($s[$i]) > 127)
      $cnt++;

  $s = substr($s, 0, $len - ($cnt % 3));

  if (strlen($s) >= strlen($str))
    $suffix = "";
  return $s . $suffix;
}

function tep_get_html_data_from_dbdata($str) {
  $result = str_replace("\"", '"', $str);
  $result = str_replace("\'", "'", $result);
  $result = str_replace("\n", "<br/>", $result);
  return $result;
}

function tep_get_htmlstr_to_dbstr($str) {
  return strip_tags(tep_get_html_data_from_dbdata($str));
}

function tep_get_all_get_params($exclude_array = '') {
  global $HTTP_GET_VARS;

  if ($exclude_array == '')
    $exclude_array = array();

  $get_url = '';

  reset($HTTP_GET_VARS);
  while (list($key, $value) = each($HTTP_GET_VARS)) {
    if (($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)))
      $get_url .= $key . '=' . $value . '&';
  }

  return $get_url;
}

function tep_format_path($path) {
  $path = str_replace(' ', '-', $path);

  $path = urlencode($path);

  $chars = "abcdefghigjklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_.@";
  $result = "";
  for ($c = 0; $c < strlen($path); $c++) {
    $temp_c = substr($path, $c, 1);
    if (strpos($chars, $temp_c) === false) {
      continue;
    } else {
      $result .= $temp_c;
    }
  }

  return strtolower($result);
}

function tep_success_redirect($msg = "Success process!", $href = "") {
  echo '<div class="success_process success">' . $msg . '</div>';

  if ($href != "") {
    echo '<div style="padding-left: 30px;">please wait while your changes are saved<br/><br/><br/></div></div></div><div class="clear"></div>';
    echo '<script language="javascript">setTimeout("document.location.href=\'' . $href . '\'", 1500)</script>';

    exit(0);
  }

  echo '</div></div><div class="clear"></div>';
}

function tep_error_redirect($msg = "Access denifined!", $href = "index.php") {
  echo '<div class="error_process">' . $msg . '</div>';

  if ($href != "") {
    echo '<div style="padding-left: 30px;">please wait while your changes are saved</div></div></div><div class="clear"></div>';
    echo '<script language="javascript">setTimeout("document.location.href=\'' . $href . '\'", 1500)</script>';

    exit(0);
  }

  echo '</div></div><div class="clear"></div>';
}

function tep_generator_password($pw_length = 8, $use_caps = true, $use_numeric = true, $use_specials = false, $use_char = true) {
  $chars = array();
  $caps = array();
  $numbers = array();
  $num_specials = 0;
  $reg_length = $pw_length;
  $pws = array();

  if ($use_char) {// create a-z
    for ($ch = 97; $ch <= 122; $ch++)
      $chars[] = $ch;
  }

  if ($use_caps) {// create A-Z
    for ($ca = 65; $ca <= 90; $ca++)
      $caps[] = $ca;
  }

  if ($use_numeric) {// create 0-9
    for ($nu = 48; $nu <= 57; $nu++)
      $numbers[] = $nu;
  }

  $all = array_merge($chars, $caps, $numbers);
  if ($use_specials) {
    $reg_length = ceil($pw_length * 0.75);
    $num_specials = $pw_length - $reg_length;
    if ($num_specials > 5)
      $num_specials = 5;
    for ($si = 33; $si <= 47; $si++)
      $signs[] = $si;
    $rs_keys = array_rand($signs, $num_specials);
    foreach ($rs_keys as $rs) {
      $pws[] = chr($signs[$rs]);
    }
  }
  $rand_keys = array_rand($all, $reg_length);
  foreach ($rand_keys as $rand) {
    $pw[] = chr($all[$rand]);
  }
  $compl = array_merge($pw, $pws);
  shuffle($compl);
  return implode('', $compl);
}

function tep_parse_input_field_data($data, $parse) {
  return strtr(trim($data), $parse);
}

function tep_output_string($string, $translate = false, $protected = false) {
  if ($protected == true) {
    return htmlspecialchars($string);
  } else {
    if ($translate == false) {
      return tep_parse_input_field_data($string, array('"' => '&quot;'));
    } else {
      return tep_parse_input_field_data($string, $translate);
    }
  }
}

function tep_phpmail($toname, $to, $subject, $body, $froname = EMAIL_ADMIN_NAME, $from = EMAIL_ADMIN_ADRESS, $chaset = 'UTF-8') {
  require_once (DIR_WS_CLASSES . 'class.phpmailer.php');

  $body .= "<br /><br />";
  $body .= "Thanks.<br />";
  $body .= "From " . SITE_TITLE . "<br/>";
  $body .= "Sended " . date('H:i d/m/Y') . "<br/>";

  $mail = new PHPMailer();
  $mail->From = $from;
  $mail->FromName = $froname;

  if (is_array($to)) {
    for ($i = 0; $i < count($to); $i++) {
      $mail->AddAddress($to[$i], $toname[$i]);
    }
  } else {
    $mail->AddAddress($to, $toname);
  }

  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->IsHTML(true);
  $mail->CharSet = $chaset;

  if (!$mail->Send()) {
    $err = $mail->ErrorInfo;

    if ($err == '') {
      return true;
    }

    $err = str_replace("\"", "", $err);
    $err = str_replace("'", "", $err);

    return false;
    //"An error occurred while sending email!<br>$err";
  } else {

    return true;
    ;
  }
}

function tep_remove_all($path) {
  if (is_file($path)) {
    @unlink($path);
  } elseif (is_dir($path)) {
    $dir = opendir($path);
    while ($file = readdir($dir)) {
      if ($file != "." && $file != "..") {
        /* remove all recursively */
        tep_remove_all($path . "/" . $file);
      }
    }
    closedir($dir);
    @rmdir($path);
  } else {
    return FALSE;
  }
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @since 2.0.5
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized($data) {
  // if it isn't a string, it isn't serialized
  if (!is_string($data))
    return false;
  $data = trim($data);
  if ('N;' == $data)
    return true;
  $length = strlen($data);
  if ($length < 4)
    return false;
  if (':' !== $data[1])
    return false;
  $lastc = $data[$length - 1];
  if (';' !== $lastc && '}' !== $lastc)
    return false;
  $token = $data[0];
  switch ($token) {
    case 's' :
      if ('"' !== $data[$length - 2])
        return false;
    case 'a' :
    case 'O' :
      return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
    case 'b' :
    case 'i' :
    case 'd' :
      return (bool) preg_match("/^{$token}:[0-9.E-]+;\$/", $data);
  }
  return false;
}

/**
 * Serialize data, if needed.
 *
 * @since 2.0.5
 *
 * @param mixed $data Data that might be serialized.
 * @return mixed A scalar data
 */
function maybe_serialize($data) {
  if (is_array($data) || is_object($data))
    return serialize($data);

  // Double serialization is required for backward compatibility.
  // See http://core.trac.wordpress.org/ticket/12930
  if (is_serialized($data))
    return serialize($data);

  return $data;
}

/**
 * Unserialize value only if it was serialized.
 *
 * @since 2.0.0
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function maybe_unserialize($original) {
  if (is_serialized($original))// don't attempt to unserialize data that wasn't serialized going in
    return @unserialize($original);
  return $original;
}

function tep_trim($value) {
  return trim($value, " \t\r\n");
}

function tep_get_message($msg, $value1 = "", $value2 = "", $value3 = "", $value4 = "", $value5 = "") {
  global $SYSTEM_MESSAGE;
  if (isset($SYSTEM_MESSAGE[$msg])) {
    $msg = $SYSTEM_MESSAGE[$msg];
  }
  return sprintf($msg, $value1, $value2, $value3, $value4, $value5);
}

function tep_get_value_post($key, $name = "", $validations = '', $prepare = true) {
  global $_POST;

  $value = "";

  if (isset($_POST[$key])) {
    if ($prepare) {
      $value = tep_db_prepare_input($_POST[$key]);

      if (!is_array($value)) {
        $value = tep_trim($value);
      }
    } else {
      $value = $_POST[$key];
    }
  }

  tep_validations($key, $value, $validations, $name);

  return $value;
}

function tep_get_value_get($key, $name = "", $validations = '', $prepare = true) {
  global $_GET;

  $value = "";
  if (isset($_GET[$key])) {
    if ($prepare) {
      $value = tep_db_prepare_input($_GET[$key]);

      if (!is_array($value)) {
        $value = tep_trim($value);
      }
    } else {
      $value = $_GET[$key];
    }
  }

  tep_validations($key, $value, $validations, $name);

  return $value;
}

function tep_get_value_require($key, $name = "", $validations = '', $prepare = true) {
  global $_REQUEST;

  $value = "";
  if (isset($_REQUEST[$key])) {
    if ($prepare) {
      $value = tep_db_prepare_input($_REQUEST[$key]);

      $value = tep_trim($value);
    } else {
      $value = $_REQUEST[$key];
    }
  }

  tep_validations($key, $value, $validations, $name);

  return str_replace("'", "''", $value);
}

function tep_get_countries($type = 2) {
  $temp = tep_db_query("select sql_cache * from " . TABLE_COUNTRIES . " order by countries_name");

  $countries = array();

  while ($country = tep_db_fetch_array($temp)) {
    if ($type == 1) {
      $countries[$country['countries_id']] = $country['countries_name'];
    } elseif ($type == 2) {
      $countries[$country['countries_iso_code_2']] = $country['countries_name'];
    } elseif ($type == 3) {
      $countries[$country['countries_iso_code_3']] = $country['countries_name'];
    }
  }

  return $countries;
}

function tep_get_after_date_time($regdate) {
  $diff = strtotime("now") - strtotime($regdate) + 1;
  //Find the number of seconds
  $day_difference = ceil($diff / (60 * 60 * 24));
  //Find how many days that is
  $hour_difference = ceil($diff / (60 * 60));
  $minute_difference = ceil($diff / 60);

  $after_date = "";
  if ($day_difference <= 1) {
    if ($hour_difference <= 1) {
      $after_date = "about " . $minute_difference . " minutes ago";
    } else {
      $after_date = "about " . $hour_difference . " hours ago";
    }
  } elseif ($day_difference <= 4) {
    $after_date = substr($regdate, 11, 5) . " Yesterday";
  } else {
    $after_date = tep_get_format_datetime($regdate);
  }

  return $after_date;
}

function tep_get_after_date($regdate) {
  $diff = strtotime("now") - strtotime($regdate) + 1;
  //Find the number of seconds
  $day_difference = ceil($diff / (60 * 60 * 24));
  //Find how many days that is
  $hour_difference = ceil($diff / (60 * 60));
  $minute_difference = ceil($diff / 60);

  $after_date = "";
  if ($day_difference <= 1) {
    $after_date = "Today";
  } elseif ($day_difference <= 2) {
    $after_date = "Yesterday";
  } elseif ($day_difference <= 7) {
    $after_date = $day_difference . " days ago";
  } else {
    $after_date = tep_get_format_datetime($regdate);
  }

  return $after_date;
}

function tep_get_format_date($date, $format = 'l F jS, Y / H:i') {
  $date = new DateTime($date);

  return $date->format($format);
}

function tep_get_format_datetime($date, $format = 'h:i m/d/Y') {
  $date = new DateTime(substr($date, 0, 19));
  //return $date->format("F j, Y h:i A");

  return $date->format($format);
}

function tep_get_before_date($strdate, $before_day, $format = 'Y-m-d') {
  $mktime = tep_get_mktime($strdate);

  return date($format, $mktime - $before_day * 86400);
}

function tep_get_startdate_of_week($totime, $format = 'Y-m-d') {
  $Time = explode(" ", $totime);
  $s = mktime(0, 0, 0, date("m", $Time[1]), date("d", $Time[1]) - date("w", $Time[1]) + 1, date("Y", $Time[1]));
  $e = mktime(0, 0, 0, date("m", $Time[1]), date("d", $Time[1]) - date("w", $Time[1]) + 7, date("Y", $Time[1]));
  $begin = date($format, $s);
  $end = date($format, $e);

  return $begin;
}

function tep_get_enddate_of_week($totime, $format = 'Y-m-d') {
  $Time = explode(" ", $totime);
  $s = mktime(0, 0, 0, date("m", $Time[1]), date("d", $Time[1]) - date("w", $Time[1]) + 1, date("Y", $Time[1]));
  $e = mktime(0, 0, 0, date("m", $Time[1]), date("d", $Time[1]) - date("w", $Time[1]) + 7, date("Y", $Time[1]));
  $begin = date($format, $s);
  $end = date($format, $e);

  return $end;
}

function tep_is_ie() {
  $brower = strtolower($_SERVER['HTTP_USER_AGENT']);

  $strpos = strpos($brower, 'msie');
  if ($strpos === false) {
    return false;
  } else {
    return true;
  }
}

function tep_foramt_large_number($number) {
  if ($number < 1000) {
    return $number;
  } elseif ($number < 1000000) {
    return round($number / 1000) . "K";
  } else {
    return round($number / 1000000) . "M";
  }
}

function tep_get_client_device() {
  /* // $aMobileUA = array('/iphone/i' => 'iPhone', '/ipod/i' => 'iPod', '/ipad/i' => 'iPad', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'Other Mobile');
    $aMobileUA = array('/iphone/i' => 'iOS', '/ipod/i' => 'iOS', '/ipad/i' => 'iOS', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'Other Mobile');

    //Return true if Mobile User Agent is detected
    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
    if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
    return $sMobileOS;
    }
    }
    //Otherwise return false..
    return false; */

  global $mobileDetect;

  $deviceType = ($mobileDetect->isMobile() ? ($mobileDetect->isTablet() ? 'tablet' : 'phone') : 'computer');
  return $deviceType;
}

function is_php($version) {
  static $_is_php;
  $version = (string) $version;

  if (!isset($_is_php[$version])) {
    $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
  }

  return $_is_php[$version];
}

function is_really_writable($file) {
  // If we're on a Unix server with safe_mode off we call is_writable
  if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') OR ! ini_get('safe_mode'))) {
    return is_writable($file);
  }

  /* For Windows servers and safe_mode "on" installations we'll actually
   * write a file then read it. Bah...
   */
  if (is_dir($file)) {
    $file = rtrim($file, '/') . '/' . md5(mt_rand());
    if (($fp = @fopen($file, 'ab')) === FALSE) {
      return FALSE;
    }

    fclose($fp);
    @chmod($file, 0777);
    @unlink($file);
    return TRUE;
  } elseif (!is_file($file) OR ( $fp = @fopen($file, 'ab')) === FALSE) {
    return FALSE;
  }

  fclose($fp);
  return TRUE;
}

function is_https() {
  if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
    return TRUE;
  } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
    return TRUE;
  } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
    return TRUE;
  }

  return FALSE;
}

function is_cli() {
  return (PHP_SAPI === 'cli' OR defined('STDIN'));
}

function function_usable($function_name) {
  static $_suhosin_func_blacklist;

  if (function_exists($function_name)) {
    if (!isset($_suhosin_func_blacklist)) {
      $_suhosin_func_blacklist = extension_loaded('suhosin') ? explode(',', trim(ini_get('suhosin.executor.func.blacklist'))) : array();
    }

    return !in_array($function_name, $_suhosin_func_blacklist, TRUE);
  }

  return FALSE;
}