<?php

function tep_validate_password($plain, $encrypted) {
  if (tep_not_null($plain) && tep_not_null($encrypted)) {
    // split apart the hash / salt
    $stack = explode(':', $encrypted);

    if (sizeof($stack) != 2)
      return false;

    if (hash_hmac("sha256", utf8_encode($plain), utf8_encode($stack[1]), false) == $stack[0]) {
      return true;
    }
  }

  return false;
}

function tep_encrypt_password($plain, $with_salt = true) {
  $password = '';

  for ($i = 0; $i < 10; $i++) {
    $password .= tep_rand();
  }

  $salt = substr(md5($password), 0, 4);

  $password = hash_hmac("sha256", utf8_encode($plain), utf8_encode($salt), false);
  //md5($salt . $plain) . ':' . $salt;

  if ($with_salt) {
    return $password . ":" . $salt;
  }

  return $password;
}

function get_user_status_color($status) {
  switch ($status) {
    case 'checked_in' :
      return 'green';
    case 'looking' :
      return 'yellow';
    case 'busy' :
      return 'red';
    case 'designated_driver' :
      return 'orange';
  }

  return "white";
}

function get_address($address, $key = "") {
  $result = "";
  $result .= $address[$key . 'address_street_1'] . "";
  if ($address[$key . 'address_street_2'] != '') {
    $result .= " " . $address[$key . 'address_street_2'];
  }
  $result .= ", ";
  $result .= $address[$key . 'address_city'] . ", ";
  $result .= $address[$key . 'address_state'] . " ";
  $result .= $address[$key . 'address_zip'] . ", ";
  $result .= $address[$key . 'address_country'];

  return $result;
}

function get_url_title($title) {
  $title = urlencode($title);

  $avalibal_charactors = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.!@+-&";

  $result = "";

  for ($i = 0; $i < strlen($title); $i++) {
    $charac = substr($title, $i, 1);

    if (strpos($avalibal_charactors, $charac) === false) {
      continue;
    }
    $result .= $charac;
  }

  return $result;
}

function upload_file($title, $file_name, $allow = "*", $require = true, $width = 0, $height = 0, $crop = false) {
  global $message_cls, $upload_img_path;
  if (!isset($_FILES[$file_name]) || $_FILES[$file_name]['tmp_name'] == '') {
    if ($require) {
      $message_cls->set_error($file_name, "Empty file");
    }

    return "";
  }
  $file = $_FILES[$file_name];

  $info = pathinfo($file['name']);
  $ext = strtolower($info['extension']);

  if ($allow != '*') {
    $allows = explode(",", $allow);
    if (in_array($ext, $allows)) {
      
    } else {
      $message_cls->set_error($file_name, "File type is not [" . $allow . "]");
    }
  }

  $year = date('Y');
  $month = date('m');
  $day = date('d');

  $upload_dir = $year . "/" . $month . "/" . $day . "/";
  chmod(DIR_WS_UPLOAD, 0777);
  if (!is_dir(DIR_WS_UPLOAD . $upload_dir)) {
    if (mkdir(DIR_WS_UPLOAD . $upload_dir, 0777, true)) {
      chmod(DIR_WS_UPLOAD . $upload_dir, 0777);
    }
  }

  $new_image_file = $upload_dir . get_url_title($title) . "." . $ext;

  while (file_exists(DIR_WS_UPLOAD . $new_image_file)) {
    $new_image_file = $upload_dir . urlencode($title) . "_" . rand(1, 99) . "." . $ext;
  }

  if (move_uploaded_file($file["tmp_name"], DIR_WS_UPLOAD . $new_image_file)) {
    $upload_img_path = DIR_WS_UPLOAD . $new_image_file;
    if ($width != 0 && $height != 0) {
      $resized_image = image_resize(DIR_WS_UPLOAD . $new_image_file, $width, $height, $crop);
      @unlink(DIR_WS_UPLOAD . $upload_img_path);
      @rename($resized_image, DIR_WS_UPLOAD . $new_image_file);
    }
    return $new_image_file;
  } else {
    $message_cls->set_error($file_name, "Error upload file.");
  }

  return "";
}

function download_file($title, $file_url, $require = true, $width = 0, $height = 0, $crop = false) {
  global $message_cls, $upload_img_path;

  $year = date('Y');
  $month = date('m');
  $day = date('d');
  $upload_dir = $year . "/" . $month . "/" . $day . "/";
  chmod(DIR_WS_UPLOAD, 0777);
  if (!is_dir(DIR_WS_UPLOAD . $upload_dir)) {
    if (mkdir(DIR_WS_UPLOAD . $upload_dir, 0777, true)) {
      chmod(DIR_WS_UPLOAD . $upload_dir, 0777);
    }
  }
  if (strrpos($file_url, '?'))
    $ext = substr($file_url, strrpos($file_url, '.') + 1, strrpos($file_url, '?') - strrpos($file_url, '.') - 1);
  else
    $ext = substr($file_url, strrpos($file_url, '.') + 1);
  $new_image_file = $upload_dir . get_url_title($title) . "." . $ext;
  while (file_exists(DIR_WS_UPLOAD . $new_image_file)) {
    $new_image_file = $upload_dir . urlencode($title) . "_" . rand(1, 99) . "." . $ext;
  }

  $img_file = file_get_contents($file_url);
  if ($img_file == false) {
    if ($require) {
      $message_cls->set_error($file_name, "Invalid URL");
    }
    return false;
  }

  $file_loc = DIR_WS_UPLOAD . $new_image_file;

  $file_handler = fopen($file_loc, 'w');

  if (fwrite($file_handler, $img_file) == false) {
    return false;
  }

  fclose($file_handler);

  $upload_img_path = DIR_WS_UPLOAD . $new_image_file;

  if ($width != 0 && $height != 0) {
    $resized_image = image_resize(DIR_WS_UPLOAD . $new_image_file, $width, $height, $crop);
    @unlink(DIR_WS_UPLOAD . $upload_img_path);
    @rename($resized_image, DIR_WS_UPLOAD . $new_image_file);
  }

  return HTTP_WS_UPLOAD . $new_image_file;
}

function formated_image($original_img_url, $original_img_path, $width, $height, $crop = false) {
  $formatted_img = image_resize($original_img_path, $width, $height, $crop);

  $url_info = pathinfo($original_img_url);
  $img_info = pathinfo($formatted_img);

  return $url_info['dirname'] . "/" . $img_info['basename'];
}

function formatted_mobile_image($original_img_url, $original_img_path) {
  $formatted_img = image_resize($original_img_path, MOBILE_IMAGE_WIDTH, MOBILE_IMAGE_HEIGHT, true);

  $url_info = pathinfo($original_img_url);
  $img_info = pathinfo($formatted_img);

  return $url_info['dirname'] . "/" . $img_info['basename'];
}

function thumb_mobile_image($original_img_url, $original_img_path) {
  $formatted_img = image_resize($original_img_path, AVATAR_IMAGE_WIDTH, AVATAR_IMAGE_HEIGHT, true);

  $url_info = pathinfo($original_img_url);
  $img_info = pathinfo($formatted_img);

  return $url_info['dirname'] . "/" . $img_info['basename'];
}

function upload_avatar($user_id, $user_name, $file_name) {
  global $message_cls;

  if (!isset($_FILES[$file_name]) || $_FILES[$file_name]['tmp_name'] == '') {
    return "";
  }

  $file = $_FILES[$file_name];

  $avatar_dir = "avatar/";
  $avatar_dir .= ($user_id - ($user_id % 10000)) . "/";
  chmod(DIR_WS_UPLOAD, 0777);
  if (!is_dir(DIR_WS_UPLOAD . $avatar_dir)) {
    if (mkdir(DIR_WS_UPLOAD . $avatar_dir, 0777, true)) {
      chmod(DIR_WS_UPLOAD . $avatar_dir, 0777);
    }
  }

  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
  $new_image_file = $user_name . "." . $ext;
  @unlink(DIR_WS_UPLOAD . $avatar_dir . $new_image_file);

  if (move_uploaded_file($file["tmp_name"], DIR_WS_UPLOAD . $avatar_dir . $new_image_file)) {
    $avartar_image = image_resize(DIR_WS_UPLOAD . $avatar_dir . $new_image_file, AVATAR_IMAGE_WIDTH, AVATAR_IMAGE_HEIGHT, true);
    @unlink(DIR_WS_UPLOAD . $avatar_dir . $new_image_file);
    @rename($avartar_image, DIR_WS_UPLOAD . $avatar_dir . $new_image_file);

    return HTTP_WS_UPLOAD . $avatar_dir . $new_image_file;
  } else {
    
  }

  return "";
}

function export_table_csv($table_name, $export_file_name) {
  export_query_csv("select * from " . $table_name);
}

function export_query_csv($query, $export_file_name) {
  $result = tep_db_query($query);

  if (!$result) {
    echo '<script lanuage="javascript">alert("No export data.")</script>';
  }

  $filed_count = mysql_num_fields($result);
  $headers = array();
  for ($i = 0; $i < $filed_count; $i++) {
    $headers[] = mysql_field_name($result, $i);
  }

  $fp = fopen("php://output", "w");
  if ($fp) {
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=" . $export_file_name);
    header("Pragma: no-cache");
    fputcsv($fp, $headers);
    while ($row = tep_db_fetch_array($result)) {
      fputcsv($fp, array_values($row));
    }
  }
}

function get_longitude_length($lat, $long, $lat_length = 111000) {
  return abs($lat_length * cos($lat));
}

function get_mile_from_meter($meter) {
  return round(($meter / 1000 * 0.625), 2);
}

function get_user_all_count() {
  global $wpdb;

  return $wpdb->get_var("SELECT count(*) FROM " . TABLE_USERS);
}

function get_schools($format = 0) {
  global $wpdb;

  $schools = $wpdb->get_results("SELECT * FROM " . TABLE_SCHOOLS . " ORDER BY `name`");
  if ($format == 0) {
    return $schools;
  }

  $temp = array();
  foreach ($schools as $school) {
    $temp[$school->ID] = $school->name;
  }

  return $temp;
}

function striptags($string) {
  return trim(str_replace("\t", "", str_replace("\n", "", str_replace("\r", "", strip_tags($string)))));
}

function get_ebay_search_result($ebay_link, $max_count = 15) {
  $html = file_get_html($ebay_link);
  if ($html === false) {
    return array();
  }

  $result = array();
  foreach ($html->find("#Results #ListViewInner li.lvresult") as $search_item) {
    $ebay_info = array();
    foreach ($search_item->find(".lvpic a") as $a_item) {
      $ebay_info['ebay_link'] = $a_item->href;
      $ebay_info['ebay_img'] = $a_item->firstChild()->getAttribute('src');
    }

    if ($ebay_info['ebay_link']) {
      
    } else {
      continue;
    }

    foreach ($search_item->find(".lvtitle a") as $title_item) {
      $ebay_info['ebay_title'] = substr($title_item->getAttribute('title'), 26);
    }

    foreach ($search_item->find(".lvprices .lvprice span") as $price_item) {
      $ebay_info['ebay_price'] = striptags($price_item->innertext);
    }

    $result[] = $ebay_info;

    if (count($result) == $max_count) {
      break;
    }
  }

  return $result;
}

function get_youtube_search_result($youtube_link) {
  $result = array();

  $temp = parse_url($youtube_link);
  if (!isset($temp['query'])) {
    return $result;
  }
  parse_str($temp['query'], $temp);
  if (isset($temp['v']) && $temp['v']) {
    try {
      $doc = new DOMDocument();
      $doc->loadHTMLFile($youtube_link);
      $doc->preserveWhiteSpace = false;
      $title_div = $doc->getElementById('eow-title');
      $title = $title_div->nodeValue;

      $youtube_id = $temp['v'];
      $youtube_info['youtube_id'] = $youtube_id;
      $youtube_info['youtube_link'] = $youtube_link;
      $youtube_info['youtube_title'] = $title;
      $youtube_info['youtube_img'] = "https://img.youtube.com/vi/" . $youtube_id . "/0.jpg";

      $result[] = $youtube_info;
    } catch (Exception $e) {
      return $result;
    }
  } else {
    $html = file_get_html($youtube_link);
    if ($html === false) {
      return $result;
    }

    foreach ($html->find("#results .section-list .item-section li .yt-lockup .yt-lockup-content h3.yt-lockup-title a") as $a_item) {
      $youtube_info = array();

      $temp = parse_url($a_item->href);
      if (!isset($temp['query']))
        continue;
      parse_str($temp['query'], $temp);
      $youtube_id = isset($temp['v']) ? $temp['v'] : "";

      if ($youtube_id) {
        
      } else {
        continue;
      }

      $youtube_info['youtube_id'] = $youtube_id;
      $youtube_info['youtube_link'] = "https://youtube.com" . $a_item->href;
      $youtube_info['youtube_title'] = striptags($a_item->innertext);
      $youtube_info['youtube_img'] = "https://img.youtube.com/vi/" . $youtube_id . "/0.jpg";

      $result[] = $youtube_info;

      if (count($result) == 15) {
        break;
      }
    }
  }

  return $result;
}

function get_amazon_search_result ($amazon_link, $max_count = 15) {
  $opts = [
    'http'=>array(
      'method'=>"GET",
      'header'=>"Accept-language: en\r\n" .
        "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
        "User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36\r\n"
    )
  ];
  // $opts = [
    // 'http'=>array(
      // 'method'=>"GET",
      // 'header'=>"Accept-language: en-US,en;q=0.8" .
		// "Connection:keep-alive\r\n".
        // "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
        // "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\r\n"
    // )
  // ];
  $context = stream_context_create($opts);
  $html = file_get_html($amazon_link, true, $context);
  if ($html === false) {
    return array();
  }
  $result = array();
  
  if ($html->find("#topDynamicContent #s-result-info-bar")) {
	
    foreach ($html->find("#resultsCol #atfResults #s-results-list-atf li") as $search_item) {
      $amazon_info = array();
	  if ($search_item->find(".a-col-left a")) {
		foreach ($search_item->find(".a-col-left a") as $a_item) {
			$amazon_info['amazon_link'] = $a_item->href;
			$amazon_info['amazon_img'] = $a_item->firstChild()->getAttribute('src');
		}
	    if ($amazon_info['amazon_link']) {

		} else {
			continue;
		}
		
		foreach ($search_item->find(".a-col-right .a-spacing-small .s-access-detail-page") as $title_item) {
			$amazon_info['amazon_title'] = substr($title_item->getAttribute('title'), 0);
		}

		foreach ($search_item->find(".a-col-right .a-offscreen") as $price_item) {
			$amazon_info['amazon_price'] = striptags($price_item->innertext);
		}
		$result[] = $amazon_info;
		if (count($result) == $max_count) {
		    break;
		}  
	  } else {
        foreach ($search_item->find(".a-spacing-base a") as $a_item) {
          $amazon_info['amazon_link'] = $a_item->href;
          $amazon_info['amazon_img'] = $a_item->firstChild()->getAttribute('src');
        }
        foreach ($search_item->find(".a-spacing-none .s-color-twister-title-link a") as $title_item) {
          $amazon_info['amazon_title'] = substr($title_item->getAttribute('title'), 0);
        }
        foreach ($search_item->find(".a-offscreen") as $price_item) {
          $amazon_info['amazon_price'] = striptags($price_item->innertext);
        }
        $result[] = $amazon_info;
		
        if (count($result) == $max_count) {
          break;
        }
      }
	  
      
    }
	
  } else {
	  
    foreach ($html->find("#resultsCol #fkmr-results0 .s-grid-view li") as $search_item) {
      $amazon_info = array();
      foreach ($search_item->find(".s-position-relative a") as $a_item) {
        $amazon_info['amazon_link'] = $a_item->href;
        $amazon_info['amazon_img'] = $a_item->firstChild()->getAttribute('src');
      }
      if ($amazon_info['amazon_link']) {

      } else {
        continue;
      }

      foreach ($search_item->find(".a-spacing-mini .s-access-detail-page") as $title_item) {
        $amazon_info['amazon_title'] = substr($title_item->getAttribute('title'), 0);
      }

      foreach ($search_item->find(".a-spacing-none .a-offscreen") as $price_item) {
        $amazon_info['amazon_price'] = striptags($price_item->innertext);
      }
      $result[] = $amazon_info;
      if (count($result) == $max_count) {
        break;
      }
    }
  }
  
  foreach ($html->find("#btfResults .s-result-list li") as $search_item) {
      $amazon_info = array();
	  if ($search_item->find(".a-col-left a")) {
		foreach ($search_item->find(".a-col-left a") as $a_item) {
			$amazon_info['amazon_link'] = $a_item->href;
			$amazon_info['amazon_img'] = $a_item->firstChild()->getAttribute('src');
		}
	    if ($amazon_info['amazon_link']) {

		} else {
			continue;
		}
		
		foreach ($search_item->find(".a-col-right .a-spacing-small .s-access-detail-page") as $title_item) {
			$amazon_info['amazon_title'] = substr($title_item->getAttribute('title'), 0);
		}

		foreach ($search_item->find(".a-col-right .a-offscreen") as $price_item) {
			$amazon_info['amazon_price'] = striptags($price_item->innertext);
		}
		$result[] = $amazon_info;
		if (count($result) == $max_count) {
		    break;
		}  
	  } else {
        foreach ($search_item->find(".a-spacing-base a") as $a_item) {
          $amazon_info['amazon_link'] = $a_item->href;
          $amazon_info['amazon_img'] = $a_item->firstChild()->getAttribute('src');
        }
        foreach ($search_item->find(".a-spacing-none .s-color-twister-title-link a") as $title_item) {
          $amazon_info['amazon_title'] = substr($title_item->getAttribute('title'), 0);
        }
        foreach ($search_item->find(".a-offscreen") as $price_item) {
          $amazon_info['amazon_price'] = striptags($price_item->innertext);
        }
        $result[] = $amazon_info;
		
        if (count($result) == $max_count) {
          break;
        }
      }  
    }
  return $result;
}

function result_combine ($amazon_result, $ebay_result) {
  $shopping_result = array();
  
  foreach ($amazon_result as $item) {
    $item_result = array();
    $item_result['type'] = "amazon";
    $item_result['link'] = $item['amazon_link'];
    $item_result['img'] = $item['amazon_img'];
    $item_result['title'] = $item['amazon_title'];
    $item_result['price'] = $item['amazon_price'];
    $shopping_result[] = $item_result;
  }
  foreach ($ebay_result as $item) {
    $item_result = array();
    $item_result['type'] = "ebay";
    $item_result['link'] = $item['ebay_link'];
    $item_result['img'] = $item['ebay_img'];
    $item_result['title'] = $item['ebay_title'];
    $item_result['price'] = $item['ebay_price'];
    $shopping_result[] = $item_result;
  }
  shuffle($shopping_result);
  return $shopping_result;
}

function get_shopping_search_result($keyword) {
  $ebay_search_url = "http://www.ebay.com/sch/i.html?_nkw=";
  $amazon_search_url = "https://www.amazon.com/s/ref=nb_sb_noss?field-keywords=";
  $keyword = urlencode($keyword);
  $ebay_result = get_ebay_search_result($ebay_search_url . $keyword, 15);
  $amazon_result = get_amazon_search_result($amazon_search_url . $keyword, 15);
  $shopping_result = result_combine ($amazon_result, $ebay_result);
  return $shopping_result;
}
