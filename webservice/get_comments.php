<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->logined_key = tep_get_value_require("loginkey", "Security Key", "require;");
    $this->code_id = tep_get_value_require("code_id", "CodeID", "require;");
    $this->device = tep_get_value_require("device", "DeviceID", "require;");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE && $this->validate_login()) {
      $sql = "select c.*, u.username from " . TABLE_COMMENTS . " c join " . TABLE_USERS . " u on c.user_id=u.`ID`";
      $sql .= " where c.status=1 and c.product_id='" . $this->code_id . "'";
      
      $comments = $wpdb->get_results($sql);
      if ($comments && !empty($comments)) {
        foreach ($comments as $comment) {
          $this->result[] = array(
              "id" => $comment->ID,
              "comment" => $comment->comment,
              "rating" => $comment->rating,
              "posted" => $comment->posted,
              "username" => $comment->username
          );
        }
      }
    }

    $this->json_result();
  }

}
