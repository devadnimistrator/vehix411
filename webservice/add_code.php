<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->logined_key = tep_get_value_require("loginkey", "Security Key", "require;");
    $this->code = tep_get_value_require("code", "Code", "require;");
    $this->description = tep_get_value_require("description", "Description");
    $this->possible_causes = tep_get_value_require("possible_causes", "Posivle Corsee");
    $this->year = tep_get_value_require("year", "Year");
    $this->make_id = tep_get_value_require("make_id", "Make");
    $this->ebay_link = tep_get_value_require("ebay_link", "Ebay Link");
    $this->ebay_keywords = tep_get_value_require("ebay_keywords", "Ebay Keyword");
    $this->youtube_link = tep_get_value_require("youtube_link", "Youtube Link");
    $this->youtube_keywords = tep_get_value_require("youtube_keywords", "Youtube Keyword");
    $this->device = tep_get_value_require("device", "DeviceID", "require;");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE && $this->validate_login()) {
      $codeinfo = array(
          "code" => strtolower($this->code),
          "description" => $this->description,
          "possible_causes" => $this->possible_causes,
          "year" => $this->year,
          "make_id" => $this->make_id,
          "model_id" => 0,
          "ebay_link" => $this->ebay_link,
          "ebay_keywords" => $this->ebay_keywords,
          "youtube_link" => $this->youtube_link,
          "youtube_keywords" => $this->youtube_keywords,
          "status" => 0,
          "user_id" => $this->logined_user_id,
          "posted" => date('Y-m-d H:i:s'),
      );

      $wpdb->insert(TABLE_PRODUCTS, $codeinfo);
    }

    $this->json_result();
  }

}
