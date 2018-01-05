<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->logined_key = tep_get_value_require("loginkey", "Security Key", "require;");
    $this->code_id = tep_get_value_require("code_id", "CodeID", "require;");
    $this->comment = tep_get_value_require("comment", "Comment", "require;length[3];");
    $this->rating = tep_get_value_require("rating", "Rating", "require;");
    $this->device = tep_get_value_require("device", "DeviceID", "require;");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE && $this->validate_login()) {
      $commentinfo = array(
          "product_id" => $this->code_id,
          "user_id" => $this->logined_user_id,
          "comment" => $this->comment,
          "rating" => $this->rating,
          "posted" => date('Y-m-d H:i:s'),
          "status" => 0
      );

      $wpdb->insert(TABLE_COMMENTS, $commentinfo);
    }

    $this->json_result();
  }

}
