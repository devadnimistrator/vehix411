<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->device = tep_get_value_require("device");
    $this->code = tep_get_value_require("code", "Code", "require;");
    $this->make_id = tep_get_value_require("make_id", "MakeID", "require;");
    $this->year = tep_get_value_require("year", "Year", "require;");
    $this->model_id = tep_get_value_require("model_id", "ModelID");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE) {
      $make_name = "";
      $model_name = "";

      $ebay_search_url = "http://www.ebay.com/sch/i.html?_nkw=";
      $_keyword = "";

      $sql = "SELECT * FROM " . TABLE_PRODUCTS . " WHERE 1=1";
      if ($this->year != '' && $this->year != 0) {
        $sql .= " AND CONCAT(',', `year`, ',') LIKE CONCAT('%,', '" . $this->year . "', ',%')";
        $_keyword .= $this->year;
      }
      if ($this->make_id != '' && $this->make_id != 0) {
        $sql .= " AND CONCAT(',', make_id, ',') LIKE CONCAT('%,', '" . $this->make_id . "', ',%')";
        $make_name = $wpdb->get_var("SELECT `code` FROM " . TABLE_MAKES . " WHERE `ID`='" . $this->make_id . "'");
        if ($make_name) {
          $_keyword .= ($_keyword ? " " . $make_name : $make_name);
        }
      }
      if ($this->model_id != '' && $this->model_id != 0) {
        //$sql .= " AND CONCAT(',', model_id, ',') LIKE CONCAT('%,', '" . $this->model_id . "', ',%')";
        $model_name = $wpdb->get_var("SELECT `name` FROM " . TABLE_MODELS . " WHERE `ID`='" . $this->model_id . "'");
        if ($model_name) {
          $_keyword .= ($_keyword ? " " . $model_name : $model_name);
        }
      }
      $sql .= " AND `status`=1 AND `code` LIKE '" . strtolower($this->code) . "%'";
      $sql .= " ORDER BY `code` ASC";
      $sql .= " LIMIT 1";

      $products = $wpdb->get_results($sql);
      if ($products && !empty($products)) {
        foreach ($products as $product) {
          $search_keywords = $this->make_keyword($product);

          $makes = $wpdb->get_results("SELECT `ID` as `id`, `name` FROM " . TABLE_MAKES . " WHERE `ID` IN (" . $product->make_id . ") ORDER BY `name`");

          $code_info = array(
              "id" => $product->ID,
              "code" => $product->code,
              "description" => $product->description,
              "possible_causes" => $product->possible_causes,
              "year" => explode(",", $product->year),
              "makes" => $makes,
              "comments" => $wpdb->get_var("select count(*) from " . TABLE_COMMENTS . " where product_id=" . $product->ID),
              "avg_rating" => round($wpdb->get_var("select avg(rating) from " . TABLE_COMMENTS . " where `status`=1 and product_id=" . $product->ID) * 1, 1),
              "ebay_link" => $product->ebay_link,
              "ebay_keywords" => $product->ebay_keywords,
              "ebay_result" => array(),
              "youtube_link" => "" . $product->youtube_link,
              "youtube_keywords" => $product->youtube_keywords,
              "youtube_result" => array()
          );

          if ($product->ebay_link) {
            
          } elseif ($product->ebay_keywords) {
            $ebay_keyword = $_keyword . " " . str_replace(",", " ", $product->ebay_keywords);
            $product_ebay_search_url = $ebay_search_url . urlencode($ebay_keyword);
            $code_info['ebay_link'] = $product_ebay_search_url;
          } else {
            $ebay_keyword = $_keyword . $search_keywords;
            $product_ebay_search_url = $ebay_search_url . urlencode($ebay_keyword);
            $code_info['ebay_link'] = $product_ebay_search_url;
            $code_info['ebay_keywords'] = $ebay_keyword;
          }
          if ($code_info['ebay_link']) {
            $code_info['ebay_result'] = get_ebay_search_result($code_info['ebay_link']);
          }

          if ($product->youtube_link) {
            
          } elseif ($product->youtube_keywords) {
            $youtube_keyword = $_keyword . " " . str_replace(",", " ", $product->youtube_keywords);
            $code_info['youtube_link'] = "https://www.youtube.com/results?q=" . urlencode($youtube_keyword);
          } else {
            $youtube_keyword = $_keyword . $search_keywords;
            $code_info['youtube_keywords'] = $youtube_keyword;
            $code_info['youtube_link'] = "https://www.youtube.com/results?q=" . urlencode($youtube_keyword);
          }

          if ($code_info['youtube_link']) {
            $code_info['youtube_result'] = get_youtube_search_result($code_info['youtube_link']);
          }

          $this->result[] = $code_info;
        }
      } else {
        $code_exists = $wpdb->get_var('SELECT COUNT(*) FROM ' . TABLE_PRODUCTS . " WHERE `status`=1 AND `code` LIKE '" . strtolower($this->code) . "%'");

        if ($code_exists) {
          $codelink = "https://www.autocodes.com/" . strtolower($this->code) . ".html";

          $html = file_get_html($codelink);
          if ($html) {
            $code_info = array(
                "id" => 0,
                "code" => $this->code,
                "description" => "",
                "possible_causes" => "",
                "year" => "",
                "makes" => "",
                "comments" => 0,
                "avg_rating" => 0,
                "ebay_link" => "",
                "ebay_keywords" => "",
                "ebay_result" => array(),
                "youtube_link" => "",
                "youtube_keywords" => "",
                "youtube_result" => array()
            );

            $head = $html->getElementByTagName('head');
            $title = $head->getElementByTagName('title');
            $code_info['description'] = $title->innertext;

            $h2_tags = array();
            $tag_index = 0;
            $possible_causes = "0";
            foreach ($html->find("#content h2.code") as $h2_tag) {
              $h2_tags[] = $h2_tag->innertext;

              if (strtolower($h2_tag->innertext) == 'possible causes') {
                $possible_causes = $tag_index;
              }

              $tag_index ++;
            }

            $tag_index = -1;
            foreach ($html->find("#content div.info_code") as $div_tag) {
              $tag_index ++;
              if ($tag_index == $possible_causes) {
                $possible_causes = strip_tags($div_tag->firstChild()->innertext);

                break;
              }
            }

            $code_info['possible_causes'] = $possible_causes;

            $search_keywords = $this->make_keyword($product);
            $ebay_keyword .= $search_keywords;
            $product_ebay_search_url = $ebay_search_url . urlencode($ebay_keyword);
            $code_info['ebay_link'] = $product_ebay_search_url;
            $code_info['ebay_keywords'] = $ebay_keyword;
            $code_info['ebay_result'] = get_ebay_search_result($code_info['ebay_link']);

            $youtube_keyword = $search_keywords;
            $code_info['youtube_keywords'] = $search_keywords;
            $code_info['youtube_link'] = "https://www.youtube.com/results?q=" . urlencode($youtube_keyword);
            $code_info['youtube_result'] = get_youtube_search_result($code_info['youtube_link']);

            $this->result[] = $code_info;
          }
        }
      }
    }
    $this->json_result();
  }

  function make_keyword($codeinfo) {
    $keyword = "";
    $descs = explode(" ", $codeinfo->description);
    for ($i = 0; $i < 3 && $i < count($descs); $i ++) {
      $keyword .= " " . $descs[$i];
    }

    $possibles = explode("\n", $codeinfo->possible_causes);
    foreach ($possibles as $possible) {
      $descs = explode(" ", $possible);
      for ($i = 0; $i < 3 && $i < count($descs); $i ++) {
        $keyword .= " " . $descs[$i];
      }
    }

    return $keyword;
  }

}
