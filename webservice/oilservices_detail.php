<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->device = tep_get_value_require("device");
    $this->year = tep_get_value_require("year", "Year", "require;");
    $this->make_id = tep_get_value_require("make_id", "Make ID", "require;");
    $this->model_id = tep_get_value_require("model_id", "Model ID", "require;");
    $this->oilservice_id = tep_get_value_require("oilservice_id", "OilService ID", "require;");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb;

    $this->validate();

    $ebay_search_url = "http://www.ebay.com/sch/i.html?_nkw=";
    $_keyword = "";

    $sql = "SELECT * FROM " . TABLE_PRODUCTS . " WHERE 1=1";
    if ($this->year != '' && $this->year != 0) {
      $_keyword .= $this->year;
    }
    if ($this->make_id != '' && $this->make_id != 0) {
      $make_name = $wpdb->get_var("SELECT `code` FROM " . TABLE_MAKES . " WHERE `ID`='" . $this->make_id . "'");
      if ($make_name) {
        $_keyword .= ($_keyword ? " " . $make_name : $make_name);
      }
    }
    if ($this->model_id != '' && $this->model_id != 0) {
      $model_name = $wpdb->get_var("SELECT `name` FROM " . TABLE_MODELS . " WHERE `ID`='" . $this->model_id . "'");
      if ($model_name) {
        $_keyword .= ($_keyword ? " " . $model_name : $model_name);
      }
    }

    $oil_service = $wpdb->get_row("SELECT * FROM " . TABLE_OILSERVICES . " WHERE `ID` = '" . $this->oilservice_id . "'");

    $result = array(
        "id" => $oil_service->ID,
        "name" => $oil_service->header,
        "description" => $oil_service->description,
        "year" => explode(",", $oil_service->year),
        "make" => array(
            "id" => $oil_service->make_id,
            "name" => $wpdb->get_var("SELECT `name` FROM " . TABLE_MAKES . " WHERE `ID` = " . $oil_service->make_id)
        ),
        "models" => $wpdb->get_results("SELECT `ID` as `id`, `name` FROM " . TABLE_MODELS . " WHERE `ID` IN (" . $oil_service->model_id . ") ORDER BY `name`"),
        "ebay_link" => $oil_service->ebay_link,
        "ebay_keywords" => $oil_service->ebay_keywords,
        "ebay_result" => array(),
        "youtube_link" => "" . $oil_service->youtube_link,
        "youtube_keywords" => $oil_service->youtube_keywords,
        "youtube_result" => array()
    );

    if ($oil_service->ebay_link) {
      
    } elseif ($oil_service->ebay_keywords) {
      $ebay_keyword = $_keyword . " " . str_replace(",", " ", $oil_service->ebay_keywords);
      $oil_service_ebay_search_url = $ebay_search_url . urlencode($ebay_keyword);
      $result['ebay_link'] = $oil_service_ebay_search_url;
    }
    if ($result['ebay_link']) {
      $result['ebay_result'] = get_ebay_search_result($result['ebay_link']);
    }

    if ($oil_service->youtube_link) {
      
    } elseif ($oil_service->youtube_keywords) {
      $youtube_keyword = $_keyword . " " . str_replace(",", " ", $oil_service->youtube_keywords);
      $result['youtube_link'] = "https://www.youtube.com/results?q=" . urlencode($youtube_keyword);
    }

    if ($result['youtube_link']) {
      $result['youtube_result'] = get_youtube_search_result($result['youtube_link']);
    }

    $this->result = array($result);

    $this->json_result();
  }

}
